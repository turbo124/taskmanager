<?php

namespace App\Console\Commands;

use App\Product;
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
class CalculateCommission extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate-commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates outstanding commission on invoices.';

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
        $invoices = Invoice::where('commission_paid', '=', 0)->get();
        $items = [];
        $invoice_ids = [];

        foreach ($invoices as $invoice) {
            $line_items = $invoice->line_items;

            foreach ($line_items as $line_item) {
                if (!empty($line_item->transaction_fee) && $line_item->transaction_fee > 0) {
                    $commission = $line_item->transaction_fee;

                    $product = Product::whereId($line_item->product_id)->first();
                    $account = $product->account;

                    $subtotal = $line_item->unit_price * $line_item->quantity;
                    $calculated_fee = round((($commission / 100) * $subtotal), 2);

                    $items[$account->id][] =
                        [
                            'line_total' => $subtotal,
                            'calculated_total' => $calculated_fee,
                            'product_id' => $product->id
                        ];

                    $invoice_ids[] = $invoice->id;
                }
            }
        }

        if (count($invoice_ids) === 0) {
            return true;
        }

        $success = true;

        foreach ($items as $account_id => $item) {
            $total = array_sum(array_column($item, 'calculated_total'));
        }

        if ($success) {
            Invoice::whereIn('id', $invoice_ids)->update(
                ['commission_paid' => true, 'commission_paid_date' => Carbon::now()]
            );
        }

        echo count($invoice_ids) . ' have been updated';

       return true;
    }

    private function createInvoice(Account $account)
    {
        if(empty($account->domains) || empty($account->domains->user_id)) {
            $account = $this->account->service()->convertAccount();
        }

        $invoice = InvoiceFactory::create($account, $user, $customer)
    }
}
