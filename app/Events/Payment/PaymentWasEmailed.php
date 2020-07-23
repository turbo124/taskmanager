<?php

namespace App\Events\Payment;

use App\Models\Payment;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentWasEmailed.
 */
class PaymentWasEmailed
{
    use SerializesModels;

    /**
     * @var Payment
     */
    public $payment;

    /**
     * PaymentWasEmailed constructor.
     * @param \App\Models\Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
