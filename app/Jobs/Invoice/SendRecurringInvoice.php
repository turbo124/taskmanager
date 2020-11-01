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
use App\Traits\CalculateRecurring;

class SendRecurringInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CalculateRecurring;

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
        $recurring_invoices = RecurringInvoice::whereDate('date_to_send', '=', Carbon::today())
                                              ->whereDate('date', '!=', Carbon::today())
                                              ->where('status_id', '=', RecurringInvoice::STATUS_ACTIVE)
                                              ->whereDate('start_date', '<=', Carbon::today())
                                              ->where('cycles_remaining', '>', 0)
                                              ->where(
                                                  function ($query) {
                                                      $query->whereNull('expiry_date')
                                                            ->orWhere('expiry_date', '>=', Carbon::today());
                                                  }
                                              )
                                              ->get();

        foreach ($recurring_invoices as $recurring_invoice) {
            $invoice = RecurringInvoiceToInvoiceFactory::create($recurring_invoice, $recurring_invoice->customer);
            $invoice = $this->invoice_repo->save(['recurring_invoice_id' => $recurring_invoice->id], $invoice);
            $this->invoice_repo->markSent($invoice);
            $invoice->service()->sendEmail(
                null,
                $invoice->customer->getSetting('email_subject_invoice'),
                $invoice->customer->getSetting('email_template_invoice')
            );

            $recurring_invoice->last_sent_date = Carbon::today();

            if (!$recurring_invoice->is_endless) {
                $recurring_invoice->cycles_remaining--;
            }

            $recurring_invoice->date_to_send = $recurring_invoice->cycles_remaining === 0 ? null
                : $this->calculateDate($recurring_invoice->frequency);
            $recurring_invoice->status_id = $recurring_invoice->cycles_remaining === 0 ? RecurringInvoice::STATUS_COMPLETED : $recurring_invoice->status_id;
            $recurring_invoice->save();

            if ($recurring_invoice->auto_billing_enabled) {
                AutobillInvoice::dispatchNow($invoice, $this->invoice_repo);
            }
        }
    }

    private function completeRecurringInvoice()
    {
    }
}
