<?php

namespace App\Settings;

class BaseSettings
{

    protected $validationFailures = [];

    protected function validate($saved_settings, $actual_settings)
    {
        if (empty($saved_settings)) {
            return false;
        }

        foreach ($actual_settings as $key => $actual_setting) {
            if (!isset($saved_settings->$key)) {
                $saved_settings->{$key} = !empty($actual_setting['translated_value']) ? trans(
                    $actual_setting['translated_value']
                ) : $actual_setting['default_value'];
            }

            // if required and empty
            if (empty($saved_settings->{$key}) && $saved_settings->{$key} !== false && $actual_setting['required'] === true) {
                $this->validationFailures[] = "{$key} is a required field";
            }

            if ($actual_setting['type'] === 'bool' && isset($saved_settings->{$key}) && is_string(
                    $saved_settings->{$key}
                )) {
                if (in_array($saved_settings->{$key}, ['true', 'false'])) {
                    $saved_settings->{$key} = $saved_settings->{$key} === 'true';
                }
            }

            // if value empty and has default value then use default
            if (!is_bool(
                    $saved_settings->{$key}
                ) && $saved_settings->{$key} === '' && !empty($actual_setting['default_value'])) {
                $saved_settings->{$key} = !empty($actual_setting['translated_value']) ? trans(
                    $actual_setting['translated_value']
                ) : $actual_setting['default_value'];
            }

            // cast type
            settype($saved_settings->{$key}, $actual_setting['type']);
        }

        if (count($this->validationFailures) > 0) {
            return false;
        }

        return $saved_settings;
    }
}
