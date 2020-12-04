<?php

namespace App\Services\Invoice;

use App\Events\Invoice\InvoiceWasCancelled;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;

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

    private bool $is_delete = false;

    /**
     * CancelInvoice constructor.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice, bool $is_delete = false)
    {
        $this->invoice = $invoice;
        $this->balance = $this->invoice->balance;
        $this->is_delete = $is_delete;
    }

    /**
     * @return Invoice
     */
    public function execute(): Invoice
    {
        if (!$this->is_delete && !$this->invoice->isCancellable()) {
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

        if ($this->is_delete) {
            $this->updatePayment();
        }

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

        if ($this->is_delete) {
            $customer->reducePaidToDateAmount($this->balance);
        }

        $customer->save();

        return $customer;
    }

    private function updatePayment()
    {
        $paymentables = $this->invoice->paymentables();
        $paymentable_total = $paymentables->sum('amount');
        $invoice_total = $this->invoice->payments->sum('amount');

        if ((float)$paymentable_total === (float)$invoice_total) {
            Payment::whereIn('id', $this->invoice->payments->pluck('id'))->delete();
        }

        if ((float)$paymentable_total !== (float)$invoice_total) {
            $payment = $this->invoice->payments->first();
            $payment->reduceAmount($paymentable_total);
        }

        $paymentables->delete();

        return true;
    }

}
