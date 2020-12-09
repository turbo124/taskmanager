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
    use SendSubscription;

    /**
     * @var Cases
     */
    public Cases $case;

    /**
     * CaseWasUpdated constructor.
     * @param Cases $case
     */
    public function __construct(Cases $case)
    {
        $this->case = $case;
        $this->send($case, get_class($this));
    }
}
