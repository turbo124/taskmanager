<?php

namespace App\Traits;

use App\Models\Currency;
use NumberFormatter;

/**
 * Class Number.
 */
trait Money
{
    public static function formatCurrency($value, $customer): string
    {
        $currency = $customer->currency;

        if (empty($currency)) {
            return true;
        }

        $locale = $customer->locale();
        $country = $customer->getCountryId();
        $decimal_separator = isset($customer->country->decimal_separator) ? $customer->country->decimal_separator : $currency->decimal_separator;
        $thousand_separator = isset($customer->country->thousand_separator) ? $customer->country->thousand_separator : $currency->thousand_separator;

        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        //$fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'USD');
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, $currency->precision); // decimals

        if (!empty($thousand_separator)) {
            $fmt->setSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL, $thousand_separator);
        }

        if (!empty($decimal_separator)) {
            $fmt->setSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, $decimal_separator);
        }

        $no_symbol = $customer->account->settings->show_currency_code === true;

        if ($no_symbol) {
            $fmt->setPattern(str_replace('¤#', '', $fmt->getPattern()));
            return $fmt->formatCurrency($value, $currency->code) . ' ' . $currency->code;
        }

        return $fmt->formatCurrency($value, $currency->code);
    }

    public function getFormattedTotal()
    {
        return $this->formatCurrency($this->total, $this->customer);
    }

    public function getFormattedSubtotal()
    {
        return $this->formatCurrency($this->sub_total, $this->customer);
    }

    public function getFormattedBalance()
    {
        return $this->formatCurrency($this->balance, $this->customer);
    }
}
