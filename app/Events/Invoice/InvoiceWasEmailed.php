<?php

namespace App\Events\Invoice;

use App\Models\Invitation;
use App\Models\Invoice;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasEmailed.
 */
class InvoiceWasEmailed
{
    use SerializesModels;

    /**
     * @var Invitation
     */
    public Invitation $invitation;

    /**
     * InvoiceWasEmailed constructor.
     * @param Invitation $invitation
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
