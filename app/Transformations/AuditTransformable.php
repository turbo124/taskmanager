<?php

namespace App\Transformations;

use App\Audit;
use App\CreditInvitation;

class AuditTransformable
{
    /**
     * @param Audit $audit
     * @return array
     */
    public function transformAudit(Audit $audit)
    {
        return [
            'id'              => (int)$audit->id,
            'notification_id' => (int)$audit->notification_id,
            'data'            => $audit->data,
            'updated_at'      => $audit->updated_at,
            'created_at'      => $audit->created_at,
        ];
    }
}
