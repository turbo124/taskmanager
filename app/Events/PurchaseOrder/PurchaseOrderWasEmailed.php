<?php

namespace App\Events\PurchaseOrder;

use App\Models\Invitation;
use Illuminate\Queue\SerializesModels;

/**
 * Class QuoteWasEmailed.
 */
class PurchaseOrderWasEmailed
{
    use SerializesModels;

    /**
     * @var Invitation
     */
    public Invitation $purchase_order_invitation;

    /**
     * PurchaseOrderWasEmailed constructor.
     * @param Invitation $purchase_order_invitation
     */
    public function __construct(Invitation $purchase_order_invitation)
    {
        $this->purchase_order_invitation = $purchase_order_invitation;
    }
}
