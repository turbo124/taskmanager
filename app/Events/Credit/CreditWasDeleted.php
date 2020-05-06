<?php

namespace App\Events\Credit;

use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class CreditWasDeleted.
 */
class CreditWasDeleted
{
    use SerializesModels;
    use SendSubscription;

    public $credit;

    /**
     * Create a new event instance.
     *
     * @param $credit
     */
    public function __construct($credit)
    {
        $this->credit = $credit;
        $this->send($credit, get_class($this));
    }
}
