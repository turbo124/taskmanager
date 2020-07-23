<?php

namespace App\Events\Payment;

use App\Models\Payment;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentWasArchived.
 */
class PaymentWasArchived
{
    use SerializesModels;

    /**
     * @var \App\Models\Payment
     */
    public Payment $payment;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
