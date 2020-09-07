<?php

namespace App\Events\Credit;

use App\Models\CreditInvitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditWasEmailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var CreditInvitation
     */
    public CreditInvitation $invitation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CreditInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
