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

      /**
     * @param $client_settings
     * @return object
     */
    public function buildCustomerSettings($client_settings, $account_settings)
    {
        if (!$client_settings) {
            return $account_settings;
        }

        foreach ($account_settings as $key => $value) {
            if (((property_exists($client_settings, $key) && is_string($client_settings->{$key}) &&
                    (iconv_strlen($client_settings->{$key}) < 1))) ||
                !isset($client_settings->{$key}) && property_exists($account_settings, $key)) {
                $client_settings->{$key} = $account_settings->{$key};
            }
        }

        return $client_settings;
    }
}
