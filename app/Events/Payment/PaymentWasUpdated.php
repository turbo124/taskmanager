<?php

namespace App\Events\Payment;

use App\Account;
use App\Payment;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use robertogallea\LaravelMetrics\Models\Traits\Measurable;
use robertogallea\LaravelMetrics\Models\Interfaces\PerformsMetrics;

/**
 * Class PaymentWasUpdated.
 */
class PaymentWasUpdated implements PerformsMetrics
{
    use SerializesModels;
    use Dispatchable;
    use Measurable;
    use SendSubscription;

    protected $meter = 'payment-updated';

    /**
     * @var array $payment
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
        //$this->send($payment, get_class($this));
    }
}
