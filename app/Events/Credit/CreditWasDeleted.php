<?php

namespace App\Events\Credit;

use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;
use App\Models\Credit;

/**
 * Class CreditWasDeleted.
 */
class CreditWasDeleted
{
    use SerializesModels;
    use SendSubscription;

    public Credit $credit;

    /**
     * Create a new event instance.
     *
     * @param $credit
     */
    public function __construct(Credit $credit)
    {
        $this->credit = $credit;
        $this->send($credit, get_class($this));
    }
}
