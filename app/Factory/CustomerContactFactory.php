<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\CustomerContact;
use App\Models\User;
use Illuminate\Support\Str;

class CustomerContactFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return CustomerContact
     */
    public static function create(Account $account, User $user): CustomerContact
    {
        $client_contact = new CustomerContact;
        $client_contact->first_name = "";
        $client_contact->user_id = $user->id;
        $client_contact->account_id = $account->id;
        $client_contact->contact_key = Str::random(40);
        $client_contact->id = 0;

        return $client_contact;
    }
}
