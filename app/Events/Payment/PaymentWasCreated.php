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
 * Class PaymentWasCreated.
 */
class PaymentWasCreated implements PerformsMetrics
{
    use SerializesModels;
    use Dispatchable;
    use Measurable;
    use SendSubscription;

    protected $meter = 'payment-created';

    /**
     * @var array $payment
     */
    public $payment;
    public $account;

    /**
     * Create a new event instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment, Account $account)
    {
        $this->payment = $payment;
        $this->account = $account;
        $this->send($payment, get_class($this));
    }
}
