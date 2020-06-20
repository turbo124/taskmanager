<?php

namespace App\Services\Account;

use App\Factory\Account\CloneAccountToAddressFactory;
use App\Factory\Account\CloneAccountToContactFactory;
use App\Factory\Account\CloneAccountToCustomerFactory;
use App\Factory\Account\CloneAccountToUserFactory;
use App\Repositories\AccountRepository;
use App\Account;
use App\Task;
use Illuminate\Support\Facades\DB;

/**
 * Class ConvertLead
 * @package App\Services\Task
 */
class ConvertAccount
{
    private Account $account;

    /**
     * ConvertLead constructor.
     * @param Task $task
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function execute()
    {
        /*if ($this->lead->task_status === Lead::STATUS_COMPLETED) {
            return false;
        }*/

        try {
            DB::beginTransaction();

            $user = CloneAccountToUserFactory::create($this->account);

            if (!$user->save()) {
                DB::rollback();
                return null;
            }

            $customer = CloneAccountToCustomerFactory::create($this->account, $user);

            if (!$customer->save()) {
                DB::rollback();
                return null;
            }

            $address = CloneAccountToAddressFactory::create($this->account, $customer);

            if (!$address->save()) {
                DB::rollback();
                return null;
            }

            $client_contact =
                CloneAccountToContactFactory::create($this->account, $customer, $user);


            if (!$client_contact->save()) {
                DB::rollback();
                return null;
            }

            $this->account->domains->user_id = $user->id;
            $this->account->domains->customer_id = $customer->id;

            if (!$this->account->domains->save()) {
                DB::rollback();
                return null;
            }

            DB::commit();

            return $this->account;
        } catch (\Exception $e) {
            echo $e->getMessage();
            die('here');
            DB::rollback();
            return null;
        }
    }
}
