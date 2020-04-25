<?php

namespace App\Events\Quote;

use Illuminate\Queue\SerializesModels;

/**
 * Class QuoteWasCreated.
 */
class QuoteWasCreated
{
    use SerializesModels;
    public $quote;

    /**
     * Create a new event instance.
     *
     * @param $quote
     */
    public function __construct($quote)
    {
        $this->quote = $quote;
    }
}
