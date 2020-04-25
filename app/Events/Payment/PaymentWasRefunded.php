<?php

namespace App\Events\Payment;

use App\Payment;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentWasRefunded.
 */
class PaymentWasRefunded
{
    use SerializesModels;

    /**
     * @var Payment
     */
    public $payment;

    public $refund_amount;

    /**
     * Create a new event instance.
     *
     * @param Payment $payment
     * @param $refund_amount
     */
    public function __construct(Payment $payment, $refund_amount)
    {
        $this->payment = $payment;
        $this->refund_amount = $refund_amount;
    }
}
