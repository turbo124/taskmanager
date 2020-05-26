<?php

namespace App\Events\Payment;

use App\Payment;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentFailed.
 */
class PaymentFailed
{
    use SerializesModels;

    /**
     * @var Payment
     */
    public Payment $payment;

    /**
     * Create a new event instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
