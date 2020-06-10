<?php

namespace App\Traits;

use App\Currency;
use NumberFormatter;

/**
 * Class Number.
 */
trait Number
{
    public static function formatCurrency($value): string
    {
        $currency = $this->customer->currency;

        if (empty($currency)) {
            return true;
        }

        $locale = $this->customer->locale();
        $country = $this->customer->getCountryId();
        $decimal_separator = isset($this->customer->country->decimal_separator) ? $customer->country->decimal_separator : $currency->decimal_separator;
        $thousand_separator = isset($this->customer->country->thousand_separator) ? $customer->country->thousand_separator : $currency->thousand_separator;

        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        //$fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'USD');
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, $currency->precision); // decimals

        if (!empty($thousand_separator)) {
            $fmt->setSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL, $thousand_separator);
        }

        if (!empty($decimal_separator)) {
            $fmt->setSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, $decimal_separator);
        }

        $no_symbol = $this->customer->account->settings->show_currency_code === true;

        if ($no_symbol) {
            $fmt->setPattern(str_replace('¤#', '', $fmt->getPattern()));
            return $fmt->formatCurrency($value, $currency->code) . ' ' . $currency->code;
        }

        return $fmt->formatCurrency($value, $currency->code);
    }
}
