<?php

namespace App\Factory;

use App\Account;
use App\ClientContact;
use App\User;
use Illuminate\Support\Str;

class ClientContactFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return ClientContact
     */
    public static function create(Account $account, User $user): ClientContact
    {
        $client_contact = new ClientContact;
        $client_contact->first_name = "";
        $client_contact->user_id = $user->id;
        $client_contact->account_id = $account->id;
        $client_contact->contact_key = Str::random(40);
        $client_contact->id = 0;

        return $client_contact;
    }
}
