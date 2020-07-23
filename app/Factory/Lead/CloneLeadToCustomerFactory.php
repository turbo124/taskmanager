<?php

namespace App\Factory\Lead;

use App\Models\ClientContact;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use App\Models\Account;

class CloneLeadToCustomerFactory
{
    /**
     * @param Lead $lead
     * @param $user_id
     * @param $account_id
     * @return \App\Models\Customer
     */
    public static function create(Lead $lead, User $user, Account $account): Customer
    {
        $client_contact = new Customer();
        $client_contact->account_id = $account->id;
        $client_contact->user_id = $user->id;
        $client_contact->name = $lead->first_name . ' ' . $lead->last_name;
        $client_contact->phone = $lead->phone;
        $client_contact->website = $lead->website;
        $client_contact->public_notes = $lead->public_notes;
        $client_contact->private_notes = $lead->private_notes;
        $client_contact->currency_id = 2;

        return $client_contact;
    }
}
