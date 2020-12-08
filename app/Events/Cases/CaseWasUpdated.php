<?php

namespace App\Events\Cases;

use App\Models\Cases;
use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class InvoiceWasMarkedSent.
 */
class CaseWasUpdated
{
    use SerializesModels;

    /**
     * @var case
     */
    public Cases $case;

    /**
     * Create a new event instance.
     *
     * @param case $case
     */
    public function __construct(Cases $case)
    {
        $this->case = $case;
        $this->send($case, get_class($this));
    }
}
