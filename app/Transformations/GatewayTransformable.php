<?php

namespace App\Transformations;

use App\Models\Audit;
use App\Models\PaymentGateway;

class GatewayTransformable
{
    /**
     * @param Audit $audit
     * @return array
     */
    public function transformGateway(PaymentGateway $gateway)
    {
        return [
            'id'         => (int)$gateway->id,
            'name'       => $gateway->name ?: '',
            'key'        => $gateway->key ?: '',
            'provider'   => $gateway->provider ?: '',
            'updated_at' => $gateway->updated_at,
            'created_at' => $gateway->created_at,
        ];
    }
}
