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

    private function updateCustomer(): Customer
    {
        $customer = $this->payment->customer;
        $customer->reducePaidToDate($this->payment->amount);

        return $customer;
    }

    private function updateInvoice()
    {
        if ($this->payment->invoices()->count() === 0) {
            return false;
        }

        foreach($this->payment->invoices as $invoice) {
            $invoice->adjustInvoices($invoice->total);

            // create transaction
        }
    }

    private function updateCredit()
    {
        if ($this->payment->credits()->count() === 0) {
            return false;
        }

        foreach($this->payment->credits as $credit){
            $credit->increaseBalance($credit->total);
            $credit->setStatus(Credit::STATUS_SENT);
            $credit->save();
        }
    }

    public function updatePayment()
    {
        $this->payment->setStatus(Payment::STATUS_VOIDED);
        $this->payment->save();

       // event here
    }
}
