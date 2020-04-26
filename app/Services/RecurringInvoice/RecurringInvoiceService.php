<?php

namespace App\Services\RecurringInvoice;

use App\Invoice;
use App\RecurringInvoice;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;
use App\Payment;
use App\Services\Customer\CustomerService;
use App\Services\Invoice\HandleCancellation;
use App\Services\Invoice\HandleReversal;
use App\Services\Invoice\ApplyNumber;
use App\Services\Invoice\MarkSent;
use App\Services\Invoice\UpdateBalance;
use Illuminate\Support\Carbon;
use App\Services\Invoice\ApplyPayment;
use App\Services\Invoice\CreateInvitations;
use App\Services\ServiceBase;

class RecurringInvoiceService extends ServiceBase
{
    protected $invoice;

    /**
     * RecurringInvoiceService constructor.
     * @param RecurringInvoice $invoice
     */
    public function __construct(RecurringInvoice $invoice)
    {
        $this->invoice = $invoice;

    }

    public function calculateInvoiceTotals(): RecurringInvoice
    {
        return $this->calculateTotals($this->invoice);
    }
}
