<?php

namespace App\Events;

use App\Models\Credit;
use Illuminate\Queue\SerializesModels;

class CreditWasCreated
{
    use SerializesModels;

    /**
     * @var \App\Models\Credit
     */
    public $credit;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Credit $credit
     */
    public function __construct(Credit $credit)
    {
        $this->credit = $credit;
    }
}
