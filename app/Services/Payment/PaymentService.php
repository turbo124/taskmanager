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
        $this->payment = $payment;
    }

    public function sendEmail()
    {
        return (new PaymentEmail($this->payment))->run();
    }
}
