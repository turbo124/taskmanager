<?php

namespace App\Events\Order;

use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderWasArchived
{
    use SerializesModels;

    public Order $order;

    /**
     * Create a new event instance.
     *
     * @param $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
