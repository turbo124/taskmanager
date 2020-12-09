<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class InvoiceWasUpdated.
 */
class InvoiceWasUpdated
{
    use SerializesModels;
    use SendSubscription;

    /**
     * @var Invoice
     */
    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->send($invoice, get_class($this));
    }
}
