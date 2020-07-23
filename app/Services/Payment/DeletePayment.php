<?php

namespace App\Services\Payment;

use App\Models\Credit;
use App\Events\Payment\PaymentWasDeleted;
use App\Models\Invoice;
use App\Models\Payment;

class DeletePayment
{
    /**
     * @var Payment
     */
    private Payment $payment;

    /**
     * DeletePayment constructor.
     * @param \App\Models\Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function execute()
    {
        $this->updateCredit();
        $this->updateInvoice();
        $this->updateCustomer();
        $this->updatePayment();

        return $this->payment;
    }

    private function updateCustomer(): bool
    {
        $customer = $this->payment->customer;
        $customer->reducePaidToDateAmount($this->payment->amount);

        return true;
    }

    private function updateInvoice(): bool
    {
        if ($this->payment->invoices()->count() === 0) {
            return true;
        }

        foreach ($this->payment->invoices as $invoice) {
            $invoice->resetBalance($invoice->pivot->amount);
            $invoice->customer->increaseBalance($invoice->pivot->amount);

            // create transaction
            $this->createTransaction($invoice);
        }

        return true;
    }

    /**
     * @param Invoice $invoice
     * @return bool
     */
    private function createTransaction(Invoice $invoice): bool
    {
        $invoice->transaction_service()->createTransaction(
            $invoice->total,
            $invoice->customer->balance,
            'Payment Deletion'
        );

        return true;
    }

    /**
     * @return bool
     */
    private function updateCredit(): bool
    {
        if ($this->payment->credits()->count() === 0) {
            return true;
        }

        foreach ($this->payment->credits as $credit) {
            $credit->increaseBalance($credit->total);
            $credit->setStatus(Credit::STATUS_SENT);
            $credit->save();
        }

        return true;
    }

    public function updatePayment(): bool
    {
        $this->payment->setStatus(Payment::STATUS_VOIDED);
        $this->payment->save();
        event(new PaymentWasDeleted($this->payment));

        $this->payment->delete();

        // event here

        return true;
    }
}
