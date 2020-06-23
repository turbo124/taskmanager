<?php

namespace App\Events\Order;

use App\Invoice;
use App\Order;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use robertogallea\LaravelMetrics\Models\Traits\Measurable;
use robertogallea\LaravelMetrics\Models\Interfaces\PerformsMetrics;

/**
 * Class OrderWasCreated
 * @package App\Events\Order
 */
class OrderWasCancelled implements PerformsMetrics
{
    use SerializesModels;
    use Dispatchable;
    use Measurable;
    use SendSubscription;

    protected $meter = 'order-cancelled';

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
