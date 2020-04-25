<?php

namespace App\Factory;

use App\Lead;

class LeadFactory
{
    public static function create(int $account_id, int $user_id): Lead
    {
        $lead = new Lead;
        $lead->account_id = $account_id;
        $lead->user_id = $user_id;
        $lead->first_name = '';
        $lead->last_name = '';
        $lead->title = '';
        $lead->description = '';
        $lead->phone = '';
        $lead->email = '';
        $lead->private_notes = '';
        $lead->public_notes = '';
        $lead->website = '';
        $lead->valued_at = 0;
        $lead->source_type = 1;
        $lead->industry_id = null;
        $lead->website = '';

        return $lead;
    }
}
