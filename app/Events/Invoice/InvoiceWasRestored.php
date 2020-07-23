<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasRestored
 * @package App\Events\Invoice
 */
class InvoiceWasRestored
{
    use SerializesModels;
    use SendSubscription;

    /**
     * @var Invoice
     */
    public Invoice $invoice;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}
