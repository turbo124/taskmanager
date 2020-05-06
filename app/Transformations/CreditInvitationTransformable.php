<?php

namespace App\Transformations;

use App\CreditInvitation;

class CreditInvitationTransformable
{
    /**
     * @param CreditInvitation $invitation
     * @return array
     */
    public function transformCreditInvitation(CreditInvitation $invitation)
    {
        return [
            'id'                => (int)$invitation->id,
            'client_contact_id' => (int)$invitation->client_contact_id,
            'customer_id'       => (int)$invitation->customer_id,
            'key'               => $invitation->key,
            'sent_date'         => $invitation->sent_date ?: '',
            'viewed_date'       => $invitation->viewed_date ?: '',
            'opened_date'       => $invitation->opened_date ?: '',
            'updated_at'        => $invitation->updated_at,
            'archived_at'       => $invitation->deleted_at,
        ];
    }
}
