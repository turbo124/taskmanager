<?php

namespace App\Events\Order;

use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class QuoteWasDeleted.
 */
class OrderWasDeleted
{
    use SerializesModels;
    use SendSubscription;

    public $order;

    /**
     * Create a new event instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->send($order, get_class($this));
    }
}
