<?php

namespace App\Settings;

use App\Models\Company;
use Exception;

class CompanySettings extends BaseSettings
{
    private $settings = [
        'payment_terms'          => ['required' => true, 'default_value' => -1, 'type' => 'integer'],
        'payment_type_id'        => ['required' => false, 'default_value' => 0, 'type' => 'string'],
        'company_number_counter' => ['required' => false, 'default_value' => 0, 'type' => 'string'],
        'company_number_pattern' => ['required' => false, 'default_value' => '', 'type' => 'string']
    ];

    public function save(Company $company, $settings): ?Company
    {
        try {
            $settings = $this->validate($settings, $this->settings);

            if (!$settings) {
                return null;
            }

            $company->settings = $settings;
            $company->save();

            return $company;
        } catch (Exception $e) {
            echo $e->getMessage();
            die('here 55');
        }
    }

    public function getCompanyDefaults()
    {
        return (object)array_filter(
            array_combine(array_keys($this->settings), array_column($this->settings, 'default_value'))
        );
    }
}
