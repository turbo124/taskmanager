<?php

namespace App\Presenters;

use App\Country;
use Laracasts\Presenter\Presenter;

/**
 * Class CustomerPresenter
 * @package App\Presenters
 */
class LeadPresenter extends Presenter
{
    /**
     * @return string
     */
    /**
     * @return string
     */
    public function name()
    {
        return $this->entity->first_name . ' ' . $this->entity->last_name;

    }

    public function address()
    {

        $str = '';
        if ($address1 = $this->entity->address_1) {
            $str .= $address1 . '<br/>';
        }
        if ($address2 = $this->entity->address_2) {
            $str .= e($address2) . '<br/>';
        }
        if ($city = $this->city) {
            $str .= e($city) . '<br/>';
        }
        if ($country = $this->entity->country) {
            $str .= e($country->name) . '<br/>';
        }

        return $str;
    }

    public function cityStateZip($city, $postalCode, $swap)
    {
        $str = $city;

        if ($swap) {
            return $postalCode . ' ' . $str;
        } else {
            return $str . ' ' . $postalCode;
        }
    }

    public function email()
    {
        return $this->entity->email;
    }

    public function phone()
    {
        return $this->entity->phone ?: '';
    }

    public function website()
    {
        return $this->entity->website ?: '';
    }
}
