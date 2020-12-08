<?php

namespace App\Events\Order;

use Illuminate\Queue\SerializesModels;

/**
 * Class OrderWasUpdated.
 */
class OrderWasUpdated
{
    use SerializesModels;

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
