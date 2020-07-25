<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Illuminate\Console\Command;

class AutobillInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autobill-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $invoice_repo = new InvoiceRepository(new Invoice());
        $invoices = $invoice_repo->getInvoicesForAutoBilling();

        if(empty($invoices)) {
            return false;
        }

        foreach ($invoices as $invoice) {
            $invoice->service()->autoBill($invoice_repo);
        }
    }
}
