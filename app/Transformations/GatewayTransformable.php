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
            'id'                      => (int)$gateway->id,
            'name'                    => $gateway->name ?: '',
            'key'                     => $gateway->key ?: '',
            'provider'                => $gateway->provider ?: '',
            'visible'                 => (bool)$gateway->visible,
            'sort_order'              => (int)$gateway->sort_order,
            'default_gateway_type_id' => $gateway->default_gateway_type_id,
            'site_url'                => $gateway->site_url ?: '',
            'is_offsite'              => (bool)$gateway->is_offsite,
            'is_secure'               => (bool)$gateway->is_secure,
            'fields'                  => (string)$gateway->fields ?: '',
            'updated_at'              => (int)$gateway->updated_at,
            'created_at'              => (int)$gateway->created_at,
        ];
    }
}
