<?php

namespace App\Services\Invoice;

use App\Invoice;
use App\Repositories\PaymentRepository;
use App\Factory\InvoiceToPaymentFactory;

class CreatePayment
{
    private $invoice;
    private $payment_repo;

    public function __construct(Invoice $invoice, PaymentRepository $payment_repo)
    {
        $this->invoice = $invoice;
        $this->payment_repo = $payment_repo;
    }

    public function run()
    {

        if ($this->invoice->balance < 0 || $this->invoice->status_id == Invoice::STATUS_PAID || $this->invoice->is_deleted === true) {
            return false;
        }

        // create payment
        $payment = $this->createPayment();

        // update invoice 
        $this->updateInvoice($payment);

        // update customer
        $this->updateCustomer();

        return $this->invoice;
    }

    private function createPayment(): Payment
    {
         $payment = $this->payment_repo->save(
            [
                'transaction_reference' => trans('texts.manual')
            ],
            InvoiceToPaymentFactory::create($this->invoice)
        );

        // attach invoices to payment
        $payment = $payment->attachInvoice($this->invoice);

        return $payment;
    }

    private function updateCustomer(Payment $payment)
    {
        $customer = $this->invoice->customer;
        $customer->increaseBalance($payment->amount * -1);
        $customer->increasePaidToDate($payment->amount);
        $customer->save();
        return true;
    }

    private function updateInvoice(Payment $payment)
    {
        $new_balance = $this->invoice->balance += floatval($payment->amount * -1);
        $this->invoice->setBalance($new_balance);
        $this->invoice->setStatus(Invoice::STATUS_PAID);
        $this->invoice->save();
        return true;
    }

}
