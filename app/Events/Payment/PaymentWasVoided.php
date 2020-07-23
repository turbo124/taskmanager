<?php

namespace App\Events\Payment;

use App\Models\Payment;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentWasEmailed.
 */
class PaymentWasVoided
{
    use SerializesModels;

    /**
     * @var \App\Models\Payment
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
