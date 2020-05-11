<?php

namespace App\Helpers\Currency;

use App\Currency;
use App\Repositories\CurrencyRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;

class CurrencyConverter
{
    private CurrencyRepository $currency_repository;

    private $base_currency = null;

    private $exchange_currency = null;

    private float $amount = 0.00;

    private $date;

    /**
     * @var array
     */
    private array $exchange_rates = [];

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

    /**
     * @param string $code
     * @return float
     */
    public function getExchangeRate(string $code): float
    {
        if (empty($this->exchange_rates)) {
            $this->download();
        }

        if (!empty($this->exchange_rates[$code])) {
            return $this->exchange_rates[$code];
        }

        return false;
    }

    public function download()
    {
        $client = new Client();
        $response = $client->get('https://openexchangerates.org/api/latest.json?app_id=2a6b8f2b3b6345df8ccd495705a251f9');
        $list = json_decode($response->getBody(), true);
        $this->exchange_rates = $list['rates'];
    }
}
