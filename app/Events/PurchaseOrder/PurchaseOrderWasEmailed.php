<?php

namespace App\Events\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInvitation;
use Illuminate\Queue\SerializesModels;

/**
 * Class QuoteWasEmailed.
 */
class PurchaseOrderWasEmailed
{
    use SerializesModels;

    /**
     * @var PurchaseOrderInvitation
     */
    public PurchaseOrderInvitation $purchase_order_invitation;

    /**
     * Create a new event instance.
     *
     * @param $purchase_order
     */
    public function __construct(PurchaseOrderInvitation $purchase_order_invitation)
    {
        $this->purchase_order_invitation = $purchase_order_invitation;
    }
}
