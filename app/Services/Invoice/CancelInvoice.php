<?php

namespace App\Services\Invoice;

use App\Events\Invoice\InvoiceWasCancelled;
use App\Models\Customer;
use App\Models\Invoice;

/**
 * Class CancelInvoice
 * @package App\Services\Invoice
 */
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
    public function execute(): Invoice
    {
        if (!$this->invoice->isCancellable()) {
            return $this->invoice;
        }

        $old_balance = $this->invoice->balance;

        // update invoice
        $this->updateInvoice();

        // update customer
        $this->updateCustomer();

        $this->invoice->transaction_service()->createTransaction(
            $old_balance,
            $this->invoice->customer->balance,
            "Invoice cancellation"
        );

        return $this->invoice;
    }

    /**
     * @return Invoice
     */
    private function updateInvoice(): Invoice
    {
        $invoice = $this->invoice;
        $this->invoice->setPreviousStatus();
        $this->invoice->setPreviousBalance();
        $this->invoice->setBalance(0);
        $this->invoice->setStatus(Invoice::STATUS_CANCELLED);
        $this->invoice->setDateCancelled();
        $this->invoice->save();

        event(new InvoiceWasCancelled($invoice));

        return $this->invoice;
    }

    /**
     * @return Customer
     */
    private function updateCustomer(): Customer
    {
        $customer = $this->invoice->customer;
        $customer->reduceBalance($this->balance);
        $customer->save();

        return $customer;
    }
}
