<?php

namespace App\Events\PurchaseOrder;

use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class QuoteWasUpdated.
 */
class PurchaseOrderWasUpdated
{
    use SerializesModels;

    public $purchase_order;

    /**
     * Create a new event instance.
     *
     * @param $purchase_order
     */
    public function __construct($purchase_order)
    {
        $this->purchase_order = $purchase_order;
        $this->send($purchase_order, get_class($this));
    }
}
