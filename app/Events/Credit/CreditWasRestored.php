<?php

namespace App\Events\Credit;

use Illuminate\Queue\SerializesModels;
use App\Models\Credit;

class CreditWasRestored
{
    use SerializesModels;

    public Credit $credit;

    /**
     * Create a new event instance.
     *
     * @param $credit
     */
    public function __construct(Credit $credit)
    {
        $this->credit = $credit;
    }
}
