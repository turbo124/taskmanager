<?php

namespace App\Presenters;

use App\Country;

/**
 * Class AccountPresenter
 * @package App\Models\Presenters
 */
class AccountPresenter extends EntityPresenter
{
    /**
     * @return string
     */
    public function name()
    {
        $settings = $this->entity->settings;

        return $this->settings->name ?: 'Untitled Account';
    }

    public function logo($settings = null)
    {
        if (!$settings) {
            $settings = $this->entity->settings;
        }

        if(empty($settings) || empty($settings->company_logo)) {
            return '';
        }

        return url($settings->company_logo);
    }

    public function address($settings = null)
    {
        $str = '';
        $company = $this->entity;

        if (!$settings) {
            $settings = $this->entity->settings;
        }

        if ($address1 = $settings->address1) {
            $str .= e($address1) . '<br/>';
        }

        if ($address2 = $settings->address2) {
            $str .= e($address2) . '<br/>';
        }

        if ($cityState = $this->getCompanyCityState($settings)) {
            $str .= e($cityState) . '<br/>';
        }

        if ($country = Country::find($settings->country_id)->first()) {
            $str .= e($country->name) . '<br/>';
        }

        if ($settings->phone) {
            $str .= "Work Phone: " . e($settings->phone) . '<br/>';
        }

        if ($settings->email) {
            $str .= "Work Email: " . e($settings->email) . '<br/>';
        }

        return $str;
    }

    public function getCompanyCityState($settings = null)
    {
        if (!$settings) {
            $settings = $this->entity->settings;
        }

        $country = Country::find($settings->country_id)->first();
        $swap = $country && $country->swap_postal_code;
        $city = e($settings->city);
        $state = e($settings->state);
        $postalCode = e($settings->postal_code);
        if ($city || $state || $postalCode) {
            return $this->cityStateZip($city, $state, $postalCode, $swap);
        } else {
            return false;
        }
    }
}
