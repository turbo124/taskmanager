<?php

namespace App\Services\Payment;

use App\Factory\PaymentFactory;
use App\Models\Invoice;
use App\Models\Payment;
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
        return (new PaymentEmail($this->payment))->execute();
    }

    /**
     * @return \App\Models\Payment
     */
    public function reverseInvoicePayment(): Payment
    {
        return (new ReverseInvoicePayment($this->payment))->execute();
    }

    /**
     * @return \App\Models\Payment
     */
    public function deletePayment(): Payment
    {
        return (new DeletePayment($this->payment))->execute();
    }
}
