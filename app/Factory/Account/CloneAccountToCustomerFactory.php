<?php

namespace App\Factory\Account;

use App\ClientContact;
use App\Customer;
use App\User;
use App\Account;

class CloneAccountToCustomerFactory
{
    /**
     * @param Lead $lead
     * @param $user_id
     * @param $account_id
     * @return Customer
     */
    public static function create(Account $account, User $user): Customer
    {
        $client_contact = new Customer();
        $client_contact->account_id = $account->id;
        $client_contact->user_id = $user->id;
        $client_contact->name = $account->name;
        $client_contact->phone = $account->phone;
        $client_contact->website = $account->website;
        $client_contact->currency_id = 2;

        return $client_contact;
    }
}
