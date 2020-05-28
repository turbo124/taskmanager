<?php

namespace App\Services\Payment;

use App\Invoice;
use App\Payment;
use App\Customer;

class ReverseInvoicePayment
{
    private Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Payment|null
     */
    public function run(): ?Payment
    {
        $this->reversePayment();
        $this->updateCustomer();
        $this->payment->ledger()->updateBalance($this->payment->amount);
        $this->payment->deletePayment();

        return $this->payment;
    }

    /**
     * @return bool
     */
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

    /**
     * @return Customer
     */
    private function updateCustomer(): Customer
    {
        $customer = $this->payment->customer;
        $customer->increaseBalance($this->payment->amount);
        $customer->reducePaidToDateAmount($this->payment->amount);
        $customer->save();
        return $customer;
    }
}
