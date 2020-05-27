<?php
namespace App\Services\Payment;  

use App\Payment;
use App\Customer;

class ReverseInvoicePayment()
{
    private Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    } 

    public function run(): ?Payment
    {
        $this->reversePayment(); 
        $this->updateCustomer();
        $this->ledger()->updateBalance($this->payment->amount);
        $this->deletePayment();

        return $this->payment;
    }

    private function reversePayment(): bool
    {
        $invoices = $this->payment->invoices;

        foreach ($invoices as $invoice) {
            if ($invoice->pivot->amount <= 0) {
                continue;
            }

            $invoice->setStatus(Invoice::STATUS_SENT);
            $invoice->setBalance($invoice->pivot->amount);
            $invoice->save();
        }

        return true;
    }

    private function updateCustomer(): Customer
    {
        $customer = $this->payment->customer;
        $customer->increaseBalance($this->amount);
        $customer->increasePaidToDateAmount($this->amount * -1);
        $customer->save();
        return $customer;
    }
}
