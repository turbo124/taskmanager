<?php

namespace App\Services\RecurringQuote;

use App\Invoice;
use App\RecurringInvoice;
use App\RecurringQuote;
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

class RecurringQuoteService extends ServiceBase
{
    protected $invoice;

    /**
     * RecurringQuoteService constructor.
     * @param RecurringQuote $invoice
     */
    public function __construct(RecurringQuote $invoice)
    {
        $this->invoice = $invoice;

    }

    public function calculateInvoiceTotals(): RecurringQuote
    {
        return $this->calculateInvoiceTotals($this->invoice);
    }
}
