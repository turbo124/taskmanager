<?php
namespace App\Settings;

class CustomerSettings extends BaseSettings
{
        private $settings = [
            'currency_id'                        => ['required' => true, 'default_value' => 2, 'type' => 'string'],
            'payment_terms'                      => ['required' => false, 'default_value' => -1, 'type' => 'integer'],
            'payment_type_id'                    => ['required' => false, 'default_value' => 0, 'type' => 'string']
        ];

    public function save(Customer $customer, $settings)
    {


    }
}
