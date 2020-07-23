<?php

namespace App\Factory\Lead;

use App\Models\ClientContact;
use App\Models\Customer;
use App\Models\User;
use App\Models\Account;
use App\Models\Lead;
use Illuminate\Support\Str;

class CloneLeadToContactFactory
{
    public static function create(Lead $lead, Customer $customer, User $user, Account $account): ClientContact
    {
        $client_contact = new ClientContact();
        $client_contact->account_id = $account->id;
        $client_contact->user_id = $user->id;
        $client_contact->customer_id = $customer->id;
        $client_contact->first_name = $lead->first_name;
        $client_contact->last_name = $lead->last_name;
        $client_contact->email = $lead->email;
        $client_contact->phone = $lead->phone;
        $client_contact->is_primary = true;
        $client_contact->contact_key = Str::random(40);


        return $client_contact;
    }
}
