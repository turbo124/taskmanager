<?php

namespace App\Services\Invoice;

use App\Customer;
use App\Invoice;
use App\Payment;
use App\Repositories\PaymentRepository;
use App\Factory\InvoiceToPaymentFactory;

class CreatePayment
{
    private Invoice $invoice;
    private PaymentRepository $payment_repo;

    public function __construct(Invoice $invoice, PaymentRepository $payment_repo)
    {
        $this->invoice = $invoice;
        $this->payment_repo = $payment_repo;
    }

    public function execute()
    {
        if ($this->invoice->balance < 0 || $this->invoice->status_id == Invoice::STATUS_PAID || $this->invoice->is_deleted === true) {
            return false;
        }

        // create payment
        $payment = $this->createPayment();

        // update invoice 
        $this->updateInvoice($payment);

        // update customer
        $this->updateCustomer($payment);

        return $this->invoice;
    }

    /**
     * @return Payment
     */
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

    /**
     * @param Payment $payment
     * @return Customer
     */
    private function updateCustomer(Payment $payment): Customer
    {
        $customer = $this->invoice->customer;
        $customer->reduceBalance($payment->amount);
        $customer->increasePaidToDateAmount($payment->amount);
        $customer->save();
        return $customer;
    }

    /**
     * @param Payment $payment
     * @return Invoice
     */
    private function updateInvoice(Payment $payment): Invoice
    {
        $new_balance = $this->invoice->reduceBalance($payment->amount);
        $this->invoice->setStatus(Invoice::STATUS_PAID);
        $this->invoice->save();
        return $this->invoice;
    }

}
