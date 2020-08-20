<?php

namespace App\Jobs\Invoice;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
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

    private function updateNextReminderDate(Invoice $invoice, $reminder_type, $number_of_days)
    {
        switch ($reminder_type) {
            case 'after_invoice_date':
                $next_send_date = Carbon::parse($invoice->date)->addDays($number_of_days)->format('Y-m-d');
                break;

            case 'before_due_date':
                $next_send_date = Carbon::parse($invoice->due_date)->subDays($number_of_days)->format('Y-m-d');
                break;

            case 'after_due_date':
                $next_send_date = Carbon::parse($invoice->due_date)->addDays($number_of_days)->format('Y-m-d');
                break;
        }

        $invoice->next_send_date = $next_send_date;
        $invoice->date_reminder_last_sent = Carbon::now();
        $invoice->save();
        return true;
    }

    private function build(Invoice $invoice)
    {
        $message_sent = false;

        for ($x = 1; $x <= 3; $x++) {
            $reminder_date = $invoice->next_send_date;
            $settings = (array)$this->settings;

            if ($settings["enable_reminder{$x}"] === false || $settings["num_days_reminder{$x}"] == 0 || $reminder_date !== Carbon::now(
                )->format('Y-m-d')) {
                continue;
            }

            if (!$message_sent) {
                $this->addCharge($invoice, $settings["late_fee_amount{$x}"]);
                $this->sendEmail($invoice, "reminder{$x}");
                $this->updateNextReminderDate($invoice, $settings["schedule_reminder{$x}"], $settings["num_days_reminder{$x}"]);
                $message_sent = true;
            }
        }
    }

    private function sendEmail(Invoice $invoice, $template)
    {
        $subject = $invoice->customer->getSetting('email_subject_' . $this->template);
        $body = $invoice->customer->getSetting('email_template_' . $this->template);
        $invoice->service()->sendEmail(null, $subject, $body, $template);
    }

    private function addCharge(Invoice $invoice, float $amount)
    {
        // if percentage calculate amount

        // update total
        $invoice->total += $amount;

        // create line
        $line_items = $invoice->line_items;
        $line_items[] = (new LineItem)
            ->setQuantity(1)
            ->setNotes('Late fee for invoice')
            ->setTypeId(Invoice::LATE_FEE_TYPE)
            ->setUnitPrice($amount)
            ->setSubTotal($amount)
            ->toObject();

        $invoice->line_items = $line_items;
    }

    public function execute(Invoice $invoice)
    {
        if ($invoice->is_deleted || !in_array(
                $invoice->status_id,
                [
                    Invoice::STATUS_SENT,
                    Invoice::STATUS_PARTIAL,
                    Invoice::STATUS_DRAFT
                ]
            )) {
            $invoice->next_send_date = null;
            $invoice->save();
            return; //exit early
        }

        $this->build($invoice);
    }
}
