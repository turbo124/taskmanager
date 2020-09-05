
<?php

namespace App\Events\PurchaseOrder;

use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class PurchaseOrderWasCreated.
 */
class PurchaseOrderWasCreated
{
    use SerializesModels;
    use SendSubscription;

    public $purchase_order;

    /**
     * Create a new event instance.
     *
     * @param $quote
     */
    public function __construct($purchase_order)
    {
        $this->purchase_order = $purchase_order;
        //$this->send($quote, get_class($this));
    }
}
