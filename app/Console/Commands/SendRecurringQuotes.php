<?php

namespace App\Console\Commands;

use App\Factory\RecurringInvoiceToInvoiceFactory;
use App\Jobs\Invoice\SendRecurringInvoice;
use App\Jobs\Quote\SendRecurringQuote;
use App\Libraries\Utils;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\RecurringInvoice;
use App\Repositories\InvoiceRepository;
use App\Repositories\QuoteRepository;
use App\Services\InvoiceService;
use Auth;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Class SendRecurringInvoices.
 */
class SendRecurringQuotes extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:recurring';
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
        SendRecurringQuote::dispatchNow((new QuoteRepository(new Quote())));
    }
}
