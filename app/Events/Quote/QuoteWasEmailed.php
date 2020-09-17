<?php

namespace App\Events\Quote;

use App\Models\QuoteInvitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteWasEmailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var QuoteInvitation
     */
    public QuoteInvitation $invitation;

    /**
     * QuoteWasEmailed constructor.
     * @param QuoteInvitation $invitation
     */
    public function __construct(QuoteInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
