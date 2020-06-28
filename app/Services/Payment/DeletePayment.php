<?php
namespace App\Services\Payment;

use App\Payment;

class DeletePayment
{
    private Payment $payment;

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
        $customer->reducePaidToDate($this->payment->amount);

        return true;
    }

    private function updateInvoice(): bool
    {
        if ($this->payment->invoices()->count() === 0) {
            return true;
        }

        foreach($this->payment->invoices as $invoice) {
            $invoice->adjustInvoices($invoice->total);

            // create transaction
            $this->createTransaction();
        }

        return true;
    }

    private function createTransaction(Invoice $invoice): bool
    {
        $invoice->transaction_service()->createTransaction(
            $invoice->total,
            $invoice->customer->balance,
            'Payment Deletion'
        );

        return true;
    }

    private function updateCredit(): bool
    {
        if ($this->payment->credits()->count() === 0) {
            return true;
        }

        foreach($this->payment->credits as $credit){
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

       // event here

       return true;
    }
}
