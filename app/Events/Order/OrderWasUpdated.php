<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class OrderWasUpdated.
 */
class OrderWasUpdated
{
    use SerializesModels;
    use SendSubscription;

    /**
     * @var Order
     */
    public Order $order;

    /**
     * OrderWasUpdated constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->send($order, get_class($this));
    }
}
