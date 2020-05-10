<?php

namespace App\Services\Invoice;

use App\Events\Payment\PaymentWasCreated;
use App\Factory\CreditFactory;
use App\Factory\PaymentFactory;
use App\Events\Invoice\InvoiceWasCancelled;
use App\Customer;
use App\Invoice;
use App\Payment;
use App\Paymentable;
use App\Services\Payment\PaymentService;

class CancelInvoice
{

    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function run()
    {
        if (!$this->invoice->isCancellable()) {
            return $this->invoice;
        }

        // update invoice
        $this->updateInvoice();
   
        $old_balance = $this->invoice->balance;
        $this->invoice->ledger()->updateBalance($old_balance, "Invoice cancellation");

        // update customer
       $this->updateCustomer();

        event(new InvoiceWasCancelled($this->invoice));

        return $this->invoice;
    }

    private function updateInvoice()
    {
        $this->invoice->setBalance(0);
        $this->invoice->setStatus(Invoice::STATUS_CANCELLED);
        $this->invoice->save();

        return true;
    }

    private function updateCustomer(): bool
    {
        $customer = $this->invoice->customer;
        $customer->setBalance($this->invoice->balance);
        $customer->save();
   
        return true;
    }
}
