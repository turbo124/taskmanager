<?php

namespace App\Factory;

use App\Quote;

class CloneQuoteFactory
{
    public static function create(Quote $quote, User $user): ?Quote
    {
        $clone_quote = $quote->replicate();
        $clone_quote->status_id = Quote::STATUS_DRAFT;
        $clone_quote->number = null;
        $clone_quote->partial_due_date = null;
        $clone_quote->user_id = $user->id;
        $clone_quote->balance = $quote->total;

        return $clone_quote;
    }
}
