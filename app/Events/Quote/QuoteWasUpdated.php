<?php

namespace App\Events\Quote;

use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class QuoteWasUpdated.
 */
class QuoteWasUpdated
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
        $this->send($quote, get_class($this));
    }
}
