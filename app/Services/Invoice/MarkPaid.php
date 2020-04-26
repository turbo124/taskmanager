<?php

namespace App\Services\Invoice;

use App\Invoice;
use App\Repositories\PaymentRepository;
use App\Factory\InvoiceToPaymentFactory;

class MarkPaid
{
    private $invoice;
    private $payment_repo;

    public function __construct(Invoice $invoice, PaymentRepository $payment_repo)
    {
        $this->invoice = $invoice;
        $this->payment_repo = $payment_repo;
    }

    public function run() {

        if ($this->invoice->balance < 0 || $this->invoice->status_id == Invoice::STATUS_PAID || $this->invoice->is_deleted === true) {
            return false;
        }

        /* Create Payment */
        $payment = $this->payment_repo->save(
            [
                'transaction_reference' => trans('texts.manual')
            ], 
            InvoiceToPaymentFactory::create($this->invoice)
        );

        // attach invoices to payment
        $payment = $payment->attachInvoice($this->invoice);

        // update balance and status
        $new_balance = $this->invoice->balance += floatval($payment->amount * -1);
        $this->invoice->setBalance($new_balance);
        $this->invoice->setStatus(Invoice::STATUS_PAID);
        $this->invoice->save();

        // update customer
        $customer = $this->invoice->customer;
        $customer->setBalance($payment->amount * -1);
        $customer->setPaidToDate($payment->amount);
        $customer->save();

        return $this->invoice;
    }
   
}
