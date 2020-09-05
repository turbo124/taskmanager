
<?php

namespace App\Events\PurchaseOrder;

use Illuminate\Queue\SerializesModels;

/**
 * Class PurchaseOrderWasUpdated.
 */
class QuoteWasUpdated
{
    use SerializesModels;

    public $purchase_order;

    /**
     * Create a new event instance.
     *
     * @param $quote
     */
    public function __construct($purchase_order)
    {
        $this->purchase_order = $purchase_order;
    }
}
