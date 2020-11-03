<?php

namespace App\Events\Cases;

use App\Models\Cases;
use App\Models\Company;
use App\Models\RecurringInvoice;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class RecurringInvoiceWasArchived
{
    use SerializesModels;

    /**
     * @var RecurringInvoice
     */
    public RecurringInvoice $recurringInvoice;

    /**
     * RecurringInvoiceWasArchived constructor.
     * @param RecurringInvoice $recurringInvoice
     */
    public function __construct(RecurringInvoice $recurringInvoice)
    {
        $this->recurringInvoice = $recurringInvoice;
    }
}
