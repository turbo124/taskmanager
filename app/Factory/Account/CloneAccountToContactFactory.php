
<?php

namespace App\Factory\Account;

use App\ClientContact;
use App\Customer;
use App\User;
use App\Account;
use Illuminate\Support\Str;

class CloneAccountToContactFactory
{
    public static function create(Account $account, Customer $customer, User $user): ClientContact
    {
        $client_contact = new ClientContact();
        $client_contact->account_id = $account->id;
        $client_contact->user_id = $user->id;
        $client_contact->customer_id = $customer->id;
        $client_contact->first_name = $account->name;
        $client_contact->last_name = '';
        $client_contact->email = $account->email;
        $client_contact->phone = $account->phone;
        $client_contact->is_primary = true;
        $client_contact->contact_key = Str::random(40);


        return $client_contact;
    }
}
