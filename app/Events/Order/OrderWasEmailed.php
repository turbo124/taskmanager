<?php

namespace App\Events\Order;

use App\Models\Invitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderWasEmailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Invitation
     */
    public Invitation $order;

    /**
     * OrderWasEmailed constructor.
     * @param Invitation $order
     */
    public function __construct(Invitation $order)
    {
        $this->order = $order;
    }
}
