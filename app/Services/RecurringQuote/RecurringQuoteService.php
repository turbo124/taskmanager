<?php

namespace App\Services\RecurringQuote;

use App\Models\RecurringQuote;
use App\Services\Customer\CustomerService;
use App\Services\Invoice\ApplyNumber;
use App\Services\Invoice\ApplyPayment;
use App\Services\Invoice\CreateInvitations;
use App\Services\Invoice\HandleCancellation;
use App\Services\Invoice\HandleReversal;
use App\Services\Invoice\MarkSent;
use App\Services\Invoice\UpdateBalance;
use App\Services\ServiceBase;

class RecurringQuoteService extends ServiceBase
{
    protected $quote;

    /**
     * RecurringQuoteService constructor.
     * @param RecurringQuote $invoice
     */
    public function __construct(RecurringQuote $quote)
    {
        parent::__construct($quote);
        $this->quote = $quote;
    }

    public function calculateInvoiceTotals(): RecurringQuote
    {
        return $this->calculateTotals($this->quote);
    }
}
