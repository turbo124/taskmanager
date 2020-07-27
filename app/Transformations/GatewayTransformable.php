<?php

namespace App\Transformations;

use App\Models\Audit;
use App\Models\CreditInvitation;
use App\Models\Gateway;

class GatewayTransformable
{
    /**
     * @param \App\Models\Audit $audit
     * @return array
     */
    public function transformGateway(Gateway $gateway)
    {
        return [
            'id'         => (int)$gateway->id,
            'name'       => $gateway->name ?: '',
            'key'        => $gateway->key ?: '',
            'provider'   => $gateway->provider ?: '',
            'updated_at' => (int)$gateway->updated_at,
            'created_at' => (int)$gateway->created_at,
        ];
    }
}
