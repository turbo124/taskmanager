<?php

namespace App\Transformations;

use App\Models\RecurringInvoiceInvitation;

class RecurringInvoiceInvitationTransformable
{
    /**
     * @param RecurringInvoiceInvitation $invitation
     * @return array
     */
    public function transformInvoiceInvitation(RecurringInvoiceInvitation $invitation)
    {
        return [
            'id'          => (int)$invitation->id,
            'contact_id'  => (int)$invitation->contact_id,
            'customer_id' => (int)$invitation->customer_id,
            'key'         => $invitation->key,
            'sent_date'   => $invitation->sent_date ?: '',
            'viewed_date' => $invitation->viewed_date ?: '',
            'opened_date' => $invitation->opened_date ?: '',
            'updated_at'  => $invitation->updated_at,
            'archived_at' => $invitation->deleted_at,
        ];
    }
}
