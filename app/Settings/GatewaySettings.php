<?php
namespace App\Settings;

class GatewaySettings extends BaseSettings
{
        private $settings = [
            'gateway_type_id'    => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'min_limit'          => ['required' => false, 'default_value' => -1, 'type' => 'float'],
            'max_limit'          => ['required' => false, 'default_value' => -1, 'type' => 'float'],
            'fee_amount'         => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_percent'        => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_tax_name1'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'fee_tax_name2'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'fee_tax_name3'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'fee_tax_rate1'      => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_tax_rate2'      => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_tax_rate3'      => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_cap'            => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'adjust_fee_percent' => ['required' => false, 'default_value' => false, 'type' => 'bool'],
        ];

    public function save(CompanyGateway $company_gateway, $settings)
    {

        try {

            $settings = $this->validate($settings, $this->settings);

            if (!$settings) {
                return false;
            }

            
        } catch (\Exception $e) {
            echo $e->getMessage();
            die('here');
        }

    }

}
