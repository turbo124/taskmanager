<?php

namespace App\Events\PurchaseOrder;

use App\Models\PurchaseOrder;
use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class QuoteWasUpdated.
 */
class PurchaseOrderWasUpdated
{
    use SerializesModels;
    use SendSubscription;

    /**
     * @var PurchaseOrder
     */
    public PurchaseOrder $purchase_order;

    /**
     * PurchaseOrderWasUpdated constructor.
     * @param PurchaseOrder $purchase_order
     */
    public function __construct(PurchaseOrder $purchase_order)
    {
        $this->purchase_order = $purchase_order;
        $this->send($purchase_order, get_class($this));
    }
}
