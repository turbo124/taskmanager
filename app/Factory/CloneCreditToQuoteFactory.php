<?php

namespace App\Factory;

use App\Credit;
use App\Quote;
use App\User;
use Carbon\Carbon;

/**
 * Class CloneCreditToQuoteFactory
 * @package App\Factory
 */
class CloneCreditToQuoteFactory
{
    /**
     * @param Credit $credit
     * @param User $user
     * @return Quote|null
     */
    public static function create(Credit $credit, User $user): ?Quote
    {
        $quote = new Quote();
        $quote->customer_id = $credit->customer_id;
        $quote->user_id = $user->id;
        $quote->account_id = $credit->account_id;
        $quote->tax_rate_name = $credit->tax_rate_name;
        $quote->tax_rate = $credit->tax_rate;
        $quote->discount = 0;
        $quote->is_deleted = false;
        $quote->footer = $credit->footer;
        $quote->public_notes = $credit->public_notes;
        $quote->private_notes = $credit->private_notes;
        $quote->terms = $credit->terms;
        $quote->custom_value1 = $credit->custom_value1;
        $quote->custom_value2 = $credit->custom_value2;
        $quote->custom_value3 = $credit->custom_value3;
        $quote->custom_value4 = $credit->custom_value4;
        $quote->total = $credit->total;
        $quote->balance = $credit->balance;
        $quote->partial = $credit->partial;
        $quote->partial_due_date = $credit->partial_due_date;
        $quote->last_viewed = $credit->last_viewed;

        $quote->status_id = Quote::STATUS_DRAFT;
        $quote->number = '';
        $quote->date = null;
        $quote->due_date = !empty($credit->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $credit->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : $credit->due_date;
        $quote->partial_due_date = null;
        $quote->line_items = $credit->line_items;

        return $quote;
    }
}
