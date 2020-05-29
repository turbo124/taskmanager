<?php

namespace App\Factory;

use App\Quote;
use App\User;
use App\Account;
use Carbon\Carbon;

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
        $clone_quote->setStatus(Quote::STATUS_DRAFT);
        $clone_quote->setNumber();
        $clone_quote->setUser($user);
        $clone_quote->setBalance($quote->total);
        $clone_quote->setDueDate();

        return $clone_quote;
    }
}
