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

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var float
     */
    private float $balance;

    /**
     * CancelInvoice constructor.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->balance = $this->invoice->balance;
    }

    /**
     * @return Invoice
     */
    public function run(): Invoice
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

    /**
     * @return Invoice
     */
    private function updateInvoice(): Invoice
    {
        $this->invoice->setBalance(0);
        $this->invoice->setStatus(Invoice::STATUS_CANCELLED);
        $this->invoice->save();

        return $this->invoice;
    }

    /**
     * @return Customer
     */
    private function updateCustomer(): Customer
    {
        $customer = $this->invoice->customer;
        $customer->increaseBalance($this->balance);
        $customer->save();

        return $customer;
    }
}
