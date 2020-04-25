<?php

namespace App\Events\Payment;

use App\Payment;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentWasDeleted.
 */
class PaymentWasDeleted
{
    use SerializesModels;

    /**
     * @var Payment
     */
    public $payment;

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
