<?php

namespace App\Events\Order;

use App\Invoice;
use App\Order;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasCreated.
 */
class OrderWasCreated
{
    use SerializesModels;
    /**
     * @var Invoice
     */
    public $order;

    /**
     * Create a new event instance.
     *
     * @param Invoice $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
