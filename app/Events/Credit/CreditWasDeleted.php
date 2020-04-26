<?php

namespace App\Events\Credit;

use Illuminate\Queue\SerializesModels;

/**
 * Class CreditWasDeleted.
 */
class CreditWasDeleted
{
    use SerializesModels;
    public $credit;

    /**
     * Create a new event instance.
     *
     * @param $credit
     */
    public function __construct($credit)
    {
        $this->credit = $credit;
    }
}
