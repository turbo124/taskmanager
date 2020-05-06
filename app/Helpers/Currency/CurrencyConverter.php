<?php

namespace App\Helpers\Currency;

use App\Currency;
use Illuminate\Support\Carbon;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;

class CurrencyConverter
{
    private $currency_repository;

    private $base_currency = null;

    private $exchange_currency = null;

    private $amount = 0.00;

    private $date;

    public function __constructor(CurrencyRepository $currency_repository = null)
    {
        $this->currency_repository = $currency_repository;
    }

    public function setBaseCurrency(Currency $currency): self
    {
        $this->base_currency = $currency;
        return $this;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setExchangeCurrency(Currency $currency): self
    {
        $this->exchange_currency = $currency;
        return $this;
    }

    public function setDate($date): self
    {
        $this->date = Carbon::parse($date);
        return $this;
    }

    private function getCurrency($currency)
    {
        return $this->currency_repository->findCurrencyById($currency);
    }

    public function calculate()
    {

        if (empty($this->amount) || empty($this->base_currency)) {

            return false;
        }

        $exchangeRates = new ExchangeRate();

        $converted_amount = $exchangeRates->convert($this->amount, $this->base_currency->code, $this->exchange_currency->code, Carbon::now());

        return $converted_amount;
    }
}
