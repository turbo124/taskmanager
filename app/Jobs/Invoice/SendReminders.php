<?php

namespace App\Jobs\Invoice;

use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Invoice $invoice;

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
        $this->processReminders();
    }

    private function processReminders()
    {
        $invoices = Invoice::where('next_send_date', Carbon::now()->format('Y-m-d'))->get();

        $invoices->each(
            function ($invoice) {
                $this->execute($invoice);
            }
        );
    }

    private function execute(Invoice $invoice)
    {
        $this->invoice = $invoice;

        if ($this->invoice->is_deleted || !in_array(
                $this->invoice->status_id,
                [
                    Invoice::STATUS_SENT,
                    Invoice::STATUS_PARTIAL,
                    Invoice::STATUS_DRAFT
                ]
            )) {
            $this->invoice->next_send_date = null;
            $this->invoice->save();
            return; //exit early
        }

        $this->build();
    }

    private function updateNextReminderDate(Invoice $invoice, $reminder_type, $number_of_days)
    {
        switch ($reminder_type) {
            case 'after_invoice_date':
                $next_send_date = Carbon::parse($this->invoice->date)->addDays($number_of_days)->format('Y-m-d');
                break;

            case 'before_due_date':
                $next_send_date = Carbon::parse($this->invoice->due_date)->subDays($number_of_days)->format('Y-m-d');
                break;

            case 'after_due_date':
                $next_send_date = Carbon::parse($this->invoice->due_date)->addDays($number_of_days)->format('Y-m-d');
                break;
        }

        $this->invoice->next_send_date = $next_send_date;
        $this->invoice->date_reminder_last_sent = Carbon::now();
        $this->invoice->save();
        return true;
    }

    private function build()
    {
        $message_sent = false;

        for ($x = 1; $x <= 3; $x++) {
            $reminder_date = $this->invoice->next_send_date;
            $settings = (array)$this->settings;

            if ($settings["enable_reminder{$x}"] === false || $settings["num_days_reminder{$x}"] == 0 || $reminder_date !== Carbon::now(
                )->format('Y-m-d')) {
                continue;
            }

            if (!$message_sent) {
                $this->addCharge($settings["late_fee_amount{$x}"]);
                $this->sendEmail("reminder{$x}");
                $this->updateNextReminderDate($settings["schedule_reminder{$x}"], $settings["num_days_reminder{$x}"]);
                $message_sent = true;
            }
        }
    }

    private function sendEmail($template)
    {
        $subject = $this->invoice->customer->getSetting('email_subject_' . $this->template);
        $body = $this->invoice->customer->getSetting('email_template_' . $this->template);
        $this->invoice->service()->sendEmail(null, $subject, $body, $template);
    }

    private function addCharge(float $amount)
    {
        // if percentage calculate amount

       $this->invoice->late_fee_charge = $amount;
       $this->invoice_repo->save([], $this->invoice);
    }
}
