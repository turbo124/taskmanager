<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceInvitation;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasEmailed.
 */
class InvoiceWasEmailed
{
    use SerializesModels;

    /**
     * @var Invoice
     */
    public $invitation;

    /**
     * Create a new event instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(InvoiceInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
