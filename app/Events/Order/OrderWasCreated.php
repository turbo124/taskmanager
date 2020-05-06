<?php

namespace App\Events\Order;

use App\Invoice;
use App\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use robertogallea\LaravelMetrics\Models\Traits\Measurable;
use robertogallea\LaravelMetrics\Models\Interfaces\PerformsMetrics;

/**
 * Class InvoiceWasCreated.
 */
class OrderWasCreated implements PerformsMetrics
{
    use SerializesModels;
    use Dispatchable;
    use Measurable;

    protected $meter = 'order-created';

    /**
     * @var Order
     */
    public $order;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
