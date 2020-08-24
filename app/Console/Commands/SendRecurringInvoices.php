<?php

namespace App\Console\Commands;

use App\Factory\RecurringInvoiceToInvoiceFactory;
use App\Jobs\Invoice\SendRecurringInvoice;
use App\Libraries\Utils;
use App\Models\Invoice;
use App\Models\RecurringInvoice;
use App\Repositories\InvoiceRepository;
use App\Services\InvoiceService;
use Auth;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Class SendRecurringInvoices.
 */
class SendRecurringInvoices extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for any invoices that need to be recurred and manages them accordingly.';

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
        SendRecurringInvoice::dispatchNow((new InvoiceRepository(new Invoice())));
    }
}
