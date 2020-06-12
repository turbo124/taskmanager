<?php

namespace App\Helpers\Payment;

use App\Credit;
use App\Customer;
use App\Events\Payment\PaymentWasRefunded;
use App\Factory\CreditFactory;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Payment;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;

class BasePaymentProcessor
{

    /**
     * @var Payment
     */
    protected Payment $payment;

    /**
     * @var float
     */
    private float $amount = 0;

    private array $data;


    private PaymentRepository $payment_repo;


    /**
     * BaseRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param PaymentRepository $payment_repo
     */
    public function __construct(Payment $payment, PaymentRepository $payment_repo, array $data)
    {
        $this->payment = $payment;
        $this->payment_repo = $payment_repo;
        $this->data = $data;
    }

    private function setStatus()
    {
        $status = $this->payment->refunded == $this->amount ? Payment::STATUS_REFUNDED : Payment::STATUS_PARTIALLY_REFUNDED;
        $this->payment->setStatus($status);
    }

    private function applyPayment()
    {
        if ($this->amount > $this->payment->amount) {
            return true;
        }

        $this->payment->applied += $this->amount;
        $this->payment->save();
    }

    /**
     * @return mixed
     */
    private function updateCustomer()
    {
        if (isset($payment->id)) {
            return true;
        }

        $amount = $this->amount == 0 ? $this->data['amount'] : $this->amount;
        $customer = $this->payment->customer;

        $customer->increasePaidToDateAmount($amount);
        //$payment->customer->increaseBalance($payment->amount);
        $customer->save();

        return $this;
    }

    /**
     * @param float $amount
     */
    protected function increasePaymentAmount(float $amount)
    {
        if (empty($amount)) {
            return $this;
        }

        $this->amount += $amount;
        return $this;
    }

    /**
     * @param float $amount
     */
    protected function reducePaymentAmount(float $amount)
    {
        if (empty($amount)) {
            return $this;
        }

        $this->amount -= $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    protected function save()
    {
        $this->applyPayment();
        //$this->setStatus();
        $this->updateCustomer();

        $this->payment->save();
        //event(new PaymentWasRefunded($this->payment, $this->data));
    }
}
