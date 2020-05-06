
<?php

namespace App\Events\Lead;

use Illuminate\Queue\SerializesModels;
use App\Lead;

class LeadWasArchived
{
    use SerializesModels;

    public Lead $lead;

    /**
     * Create a new event instance.
     *
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }
}
