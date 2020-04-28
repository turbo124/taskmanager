<?php

namespace App\Events\Order;

use Illuminate\Queue\SerializesModels;

class OrderWasArchived
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
    }
}
