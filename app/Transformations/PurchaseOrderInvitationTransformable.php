<?php

namespace App\Transformations;

use App\Models\PurchaseOrderInvitation;

class PurchaseOrderInvitationTransformable
{

    /**
     * @param PurchaseOrderInvitation $invitation
     * @return array
     */
    public function transformPurchaseOrderInvitations(PurchaseOrderInvitation $invitation)
    {
        return [
            'id'                 => (int)$invitation->id,
            'company_contact_id' => (int)$invitation->company_contact_id,
            'company_id'         => (int)$invitation->company_id,
            'key'                => $invitation->key,
            'sent_date'          => $invitation->sent_date ?: '',
            'viewed_date'        => $invitation->viewed_date ?: '',
            'opened_date'        => $invitation->opened_date ?: '',
            'updated_at'         => $invitation->updated_at,
            'archived_at'        => $invitation->deleted_at,
        ];
    }

}
