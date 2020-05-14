<?php

namespace App\Settings;

use App\GroupSetting;

class GroupSettings extends BaseSettings
{
    private $settings = [
        'currency_id'     => ['required' => false, 'default_value' => 2, 'type' => 'string'],
        'payment_terms'   => ['required' => false, 'default_value' => -1, 'type' => 'integer'],
        'payment_type_id' => ['required' => false, 'default_value' => 0, 'type' => 'string']
    ];

    public function save(GroupSetting $group_setting, $settings): ?GroupSetting
    {
        try {
            if (empty($settings)) {
                $settings = $this->getGroupDefaults();
            }

            $settings = $this->validate($settings, $this->settings);

            if (!$settings) {
                return null;
            }

            $group_setting->settings = $settings;
            $group_setting->save();

            return $group_setting;
        } catch (\Exception $e) {
            echo $e->getMessage();
            die('here');
        }
    }

    public function getGroupDefaults()
    {
        return (object)array_filter(
            array_combine(array_keys($this->settings), array_column($this->settings, 'default_value'))
        );
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
