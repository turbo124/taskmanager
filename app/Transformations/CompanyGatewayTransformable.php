<?php

namespace App\Transformations;

use App\Models\CompanyGateway;
use App\Models\Gateway;

trait CompanyGatewayTransformable
{
    /**
     * @param CompanyGateway $company_gateway
     * @return array
     */
    protected function transformCompanyGateway(CompanyGateway $company_gateway)
    {
        return [
            'id'                    => (int)$company_gateway->id,
            'gateway_key'           => (string)$company_gateway->gateway_key ?: '',
            'gateway'               => $this->transformGateway($company_gateway->gateway),
            'accepted_credit_cards' => $company_gateway->accepted_credit_cards,
            'require_cvv'           => (bool)$company_gateway->require_cvv,
            'show_billing_address'  => (bool)$company_gateway->show_billing_address,
            'show_shipping_address' => (bool)$company_gateway->show_shipping_address,
            'update_details'        => (bool)$company_gateway->update_details,
            'config'                => $company_gateway->config,
            'fees_and_limits'       => $company_gateway->fees_and_limits ?: '',
            'updated_at'            => $company_gateway->updated_at,
            'deleted_at'            => $company_gateway->deleted_at,
        ];
    }

    /**
     * @param Gateway $gateway
     * @return array
     */
    public function transformGateway(Gateway $gateway)
    {
        if (empty($gateway)) {
            return [];
        }

        return (new GatewayTransformable)->transformGateway($gateway);
    }
}
