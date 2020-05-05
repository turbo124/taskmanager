<?php

namespace App\Utils;

use App\Currency;
use NumberFormatter;
use Illuminate\Support\Facades\Log;

/**
 * Class Number.
 */
class Number
{
    /**
     * Formats a given value based on the clients currency AND country
     *
     * @param floatval $value The number to be formatted
     * @param object $currency The client currency object
     * @param object $country The client country
     *
     * @return string           The formatted value
     */
    public static function formatCurrency($value, $customer): string
    {
        $currency = $customer->currency;

        if (empty($currency)) {
            return true;
        }

       // currency first
       // if address no empty get it from that
       //$client->getSetting('show_currency_code')
       // if show_currency_code === true show code after value
       // if swap symbol is true put symbol after value
       // elseif  show_currency_code === false show symbole before
       
    
        $locale = $customer->locale();
        $country = $customer->getCountryId();
        $decimal_separator = isset($customer->country->decimal_separator) ? $customer->country->decimal_separator : $currency->decimal_separator;
        $thousand_separator = isset($customer->country->thousand_separator) ? $customer->country->thousand_separator : $currency->thousand_separator;
        
        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        //$fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'USD');
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, $currency->precision); // decimals
        
        if(!empty($thousand_separator)) {
            $fmt->setSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL, $thousand_separator);
        }

        if(!empty($decimal_separator)) {
            $fmt->setSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, $decimal_separator);
        }

        $no_symbol = $customer->account->settings->show_currency_code === true ? true : false;

        if ($no_symbol)
        {
            $fmt->setPattern( str_replace('¤#','', $fmt->getPattern()));
            return $fmt->formatCurrency($value, $currency->code) . ' ' . $currency->code;
        }

        return $fmt->formatCurrency($value, $currency->code);
    }
}
