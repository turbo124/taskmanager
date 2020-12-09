<?php

namespace App\Events\Deal;

use App\Models\Deal;
use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class InvoiceWasMarkedSent.
 */
class DealWasUpdated
{
    use SerializesModels;
    use SendSubscription;

    /**
     * @var Deal
     */
    public Deal $deal;

    /**
     * DealWasUpdated constructor.
     * @param Deal $deal
     */
    public function __construct(Deal $deal)
    {
        $this->deal = $deal; 
        $this->send($deal, get_class($this));
    }
}
