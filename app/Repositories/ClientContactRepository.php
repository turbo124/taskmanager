<?php

namespace App\Repositories;

use App\Factory\ClientContactFactory;
use App\Models\ClientContact;
use App\Models\Customer;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Hash;

/**
 * ClientContactRepository
 */
class ClientContactRepository extends BaseRepository
{
    /**
     * ClientContactRepository constructor.
     * @param ClientContact $client_contact
     */
    public function __construct(ClientContact $client_contact)
    {
        parent::__construct($client_contact);
        $this->model = $client_contact;
    }

    /**
     * @param $data
     * @param Customer $customer
     * @return bool
     */
    public function save(array $contacts, Customer $customer): bool
    {
        $old_ids = ClientContact::whereCustomerId($customer->id)->pluck('id')->toArray();

        $updates = collect(
            array_filter(
                $contacts,
                function ($item) {
                    if (isset($item['id'])) {
                        return true;
                    }
                    return false;
                }
            )
        )->keyBy('id')->toArray();

        $insert = array_filter(
            $contacts,
            function ($item) {
                if (!isset($item['id'])) {
                    return true;
                }
                return false;
            }
        );


        $new_ids = array_keys($updates);

        $update = array_intersect_key($new_ids, $old_ids);
        //^^^Returns all records of $new_ids that were present in $old_ids
        $delete = array_diff_key($old_ids, $new_ids);
        //^^^Returns all records of $old_ids that aren't present in $new_ids


        /******************************* Delete *********************************/
        if (!empty($delete)) {
            ClientContact::destroy($delete);
        }

        /********************************Update ************************************/
        if (!empty($update)) {
            $contacts = ClientContact::whereIn('id', $update)->get()->keyBy('id');

            foreach ($updates as $key => $update) {
                if (!isset($contacts[$key])) {
                    continue;
                }

                $contact = $contacts[$key];
                $contact->fill($update);
                $contact->password = isset($update['password']) && strlen($update['password']) > 0 ? Hash::make(
                    $update['password']
                ) : $contact->password;
                $contact->save();
            }
        }

        /*******************************Create****************************************/
        if (!empty($insert)) {
            foreach ($insert as $item) {
                $create_contact = ClientContactFactory::create($customer->account, $customer->user);
                $create_contact->customer_id = $customer->id;
                $create_contact->fill($item);
                $create_contact->password = isset($item['password']) && strlen($item['password']) > 0 ? Hash::make(
                    $item['password']
                ) : '';
                $create_contact->save();
            }
        }

        return true;
    }
}
