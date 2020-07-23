<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Models\Quote;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class OrderWasMarkedSent
{
    use SerializesModels;

    /**
     * @var Invoice
     */
    public $order;

    /**
     * OrderWasMarkedSent constructor.
     * @param \App\Models\Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
