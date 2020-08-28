<?php

namespace App\Jobs\Invoice;

use App\Factory\RecurringInvoiceToInvoiceFactory;
use App\Models\Invoice;
use App\Models\RecurringInvoice;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRecurringInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoice_repo;

    public function __construct(InvoiceRepository $invoice_repo)
    {
        $this->invoice_repo = $invoice_repo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->processRecurringInvoices();
    }

    private function processRecurringInvoices()
    {
        $recurring_invoices = RecurringInvoice::whereDate('next_send_date', '=', Carbon::today())
                                              ->whereDate('date', '!=', Carbon::today())
                                              ->whereDate('start_date', '<=', Carbon::today())
                                              ->where(
                                                  function ($query) {
                                                      $query->whereNull('end_date')
                                                            ->orWhere('end_date', '>=', Carbon::today());
                                                  }
                                              )
                                              ->get();

        foreach ($recurring_invoices as $recurring_invoice) {
            $quote = RecurringInvoiceToInvoiceFactory::create($recurring_invoice, $recurring_invoice->customer);
            $quote = $this->invoice_repo->save(['recurring_invoice_id' => $recurring_invoice->id], $quote);

            $quote->service()->sendEmail(null, trans('texts.quote_subject'), trans('texts.quote_body'));

            $recurring_invoice->last_sent_date = Carbon::today();
            $recurring_invoice->next_send_date = Carbon::today()->addDays($recurring_invoice->frequency);
            $recurring_invoice->save();
        }
    }
}
