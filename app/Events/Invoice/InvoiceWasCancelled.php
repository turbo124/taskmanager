<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasCancelled.
 */
class InvoiceWasCancelled
{
    use SerializesModels;

    /**
     * @var \App\Models\Invoice
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
