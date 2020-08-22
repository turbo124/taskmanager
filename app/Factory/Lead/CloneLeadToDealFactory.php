<?php

namespace App\Factory\Lead;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\TaskStatus;
use App\Models\User;

class CloneLeadToDealFactory
{
    /**
     * @param Lead $lead
     * @param Customer $customer
     * @param User $user
     * @param Account $account
     * @return Deal
     */
    public static function create(Lead $lead, Customer $customer, User $user, Account $account): Deal
    {
        $client_contact = new Deal();
        $client_contact->account_id = $account->id;
        $client_contact->customer_id = $customer->id;
        $client_contact->user_id = $user->id;
        $client_contact->valued_at = $lead->valued_at;
        $client_contact->task_status = TaskStatus::where('task_type', 2)->first()->id;
        $client_contact->title = $lead->title;
        $client_contact->description = $lead->description;
        $client_contact->source_type = $lead->source_type;

        return $client_contact;
    }
}
