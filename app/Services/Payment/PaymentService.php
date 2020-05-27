<?php

namespace App\Services\Payment;

use App\Factory\PaymentFactory;
use App\Invoice;
use App\Payment;
use App\Services\ServiceBase;

class PaymentService extends ServiceBase
{
    private $payment;

    public function __construct(Payment $payment)
    {
        parent::__construct($payment);
        $this->payment = $payment;
    }

    public function sendEmail()
    {
        return (new PaymentEmail($this->payment))->run();
    }

    public function reverseInvoicePayment(): Payment
    {
        return (new ReversePayment($this->payment))->run();
    }
}
