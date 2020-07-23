<?php

namespace App\Events\Payment;

use App\Models\Payment;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentWasRefunded.
 */
class PaymentWasRefunded
{
    use SerializesModels;

    /**
     * @var \App\Models\Payment
     */
    public $payment;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Payment $payment
     * @param $refund_amount
     */
    public function __construct(Payment $payment, $data)
    {
        $this->payment = $payment;
        $this->data = $data;
    }
}
