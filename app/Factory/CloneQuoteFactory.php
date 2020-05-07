<?php

namespace App\Factory;

use App\Quote;
use App\User;
use App\Account;

/**
 * Class CloneQuoteFactory
 * @package App\Factory
 */
class CloneQuoteFactory
{
    /**
     * @param Quote $quote
     * @param User $user
     * @return Quote|null
     */
    public static function create(Quote $quote, User $user): ?Quote
    {
        $clone_quote = $quote->replicate();
        $clone_quote->status_id = Quote::STATUS_DRAFT;
        $clone_quote->number = null;
        $clone_quote->user_id = $user->id;
        $clone_quote->balance = $quote->total;

        return $clone_quote;
    }
}
