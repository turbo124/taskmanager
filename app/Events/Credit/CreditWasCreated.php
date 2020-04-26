<?php

namespace App\Events\Credit;

use App\Credit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $credit;

    /**
     * Create a new event instance.
     *
     * @param Credit $credit
     */
    public function __construct(Credit $credit)
    {
        $this->credit = $credit;
    }
}
