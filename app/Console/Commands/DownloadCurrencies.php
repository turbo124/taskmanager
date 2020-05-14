<?php

namespace App\Console\Commands;

use App\Currency;
use App\Helpers\Currency\CurrencyConverter;
use Illuminate\Console\Command;

/**
 * Class EmailFailures.
 */
class DownloadCurrencies extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download exchange rates.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currencies = Currency::all();
        $currency_converter = new CurrencyConverter;

        foreach ($currencies as $currency) {
            $exchange_rate = $currency_converter->getExchangeRate($currency->code);

            echo $exchange_rate . ' ' . $currency->code;

            if (!$exchange_rate) {
                continue;
            }

            $currency->update(['exchange_rate' => $exchange_rate]);
        }
    }
}
