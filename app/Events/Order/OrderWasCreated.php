<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Traits\SendSubscription;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use robertogallea\LaravelMetrics\Models\Interfaces\PerformsMetrics;
use robertogallea\LaravelMetrics\Models\Traits\Measurable;

/**
 * Class OrderWasCreated
 * @package App\Events\Order
 */
class OrderWasCreated implements PerformsMetrics
{
    use SerializesModels;
    use Dispatchable;
    use Measurable;
    use SendSubscription;

    protected $meter = 'order-created';

    /**
     * @var Order
     */
    public Order $order;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->send($order, get_class($this));
    }
}
