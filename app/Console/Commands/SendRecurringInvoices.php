<?php

namespace App\Console\Commands;

use App\RecurringInvoice;
use App\Factory\RecurringInvoiceToInvoiceFactory;
use App\Invoice;
use Illuminate\Support\Carbon;
use App\Repositories\InvoiceRepository;
use App\Services\InvoiceService;
use DateTime;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Auth;
use Exception;
use App\Libraries\Utils;
use App\Jobs\Cron\RecurringInvoicesCron;

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
        $toMakeInvoices = RecurringInvoice::whereDate('next_send_date', '=', Carbon::today())
                                          ->whereDate('date', '!=', Carbon::today())
                                          ->get();

        foreach ($toMakeInvoices as $recurringInvoice) {
            $invoice = RecurringInvoiceToInvoiceFactory::create($recurringInvoice, $recurringInvoice->customer);
            (new InvoiceRepository(new Invoice))->save([], $invoice);

            $recurringInvoice->last_sent_date = Carbon::today();
            $recurringInvoice->next_send_date = Carbon::today()->addDays($recurringInvoice->frequency_id);
            $recurringInvoice->save();
            //Mail::send(new NewInvoice($newInvoice->client, $newInvoice));
        }
    }
}
