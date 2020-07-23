<?php

namespace App\Events\Quote;

use App\Models\Quote;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class QuoteWasApproved
{
    use SerializesModels;

    /**
     * @var \App\Models\Quote
     */
    public Quote $quote;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Quote $quote
     */
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }
}
