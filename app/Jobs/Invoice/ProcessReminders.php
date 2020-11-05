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

class ProcessReminders implements ShouldQueue
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
        $invoices = Invoice::whereDate('date_to_send', '=', Carbon::today()->toDateString())->get();

        foreach ($invoices as $invoice) {
            $this->execute($invoice);
        }

        return true;
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
            $this->invoice->date_to_send = null;
            $this->invoice->save();
            return; //exit early
        }

        $this->build();
    }

    private function build()
    {
        $message_sent = false;

        for ($counter = 1; $counter <= 3; $counter++) {
            $reminder_date = $this->invoice->date_to_send;

            if ($this->invoice->customer->getSetting(
                    "reminder{$counter}_enabled"
                ) === false || $this->invoice->customer->getSetting(
                    "number_of_days_after_{$counter}"
                ) == 0 || !$reminder_date->isToday()) {
                continue;
            }

            if (!$message_sent) {
                $this->addCharge($counter);

                $this->sendEmail("reminder{$counter}");

                $this->updateNextReminderDate(
                    $this->invoice->customer->getSetting("scheduled_to_send_{$counter}"),
                    $this->invoice->customer->getSetting("number_of_days_after_{$counter}")
                );
                $message_sent = true;
            }
        }
    }

    private function addCharge($counter): bool
    {
        $amount = $this->calculateAmount($counter);

        if (empty($amount)) {
            return true;
        }

        $this->invoice->late_fee_charge += $amount;
        $this->invoice_repo->save(['late_fee_charge' => $amount], $this->invoice);

        return true;
    }

    /**
     * @param $counter
     * @return false|float|null
     */
    private function calculateAmount($counter)
    {
        $percentage = $this->invoice->customer->getSetting("percent_to_charge_{$counter}");
        $current_total = $this->invoice->partial > 0 ? $this->invoice->partial : $this->invoice->balance;

        if (!empty($percentage)) {
            return round(($percentage / 100) * $current_total, 2);
        }

        $amount = $this->invoice->customer->getSetting("amount_to_charge_{$counter}");

        if (empty($amount)) {
            return null;
        }

        return $amount;
    }

    private function sendEmail($template)
    {
        $subject = $this->invoice->customer->getSetting($template . '_subject');
        $body = $this->invoice->customer->getSetting($template . '_message');
        $this->invoice->service()->sendEmail(null, $subject, $body, $template);
    }

    private function updateNextReminderDate($reminder_type, $number_of_days)
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

        $this->invoice->date_to_send = $next_send_date;
        $this->invoice->date_reminder_last_sent = Carbon::now();
        $this->invoice->save();
        return true;
    }
}
