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
        $invoices = Invoice::whereDate('date_to_send', '=', Carbon::today()->toDateString())->get();

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
            $this->invoice->date_to_send = null;
            $this->invoice->save();
            return; //exit early
        }

        $this->build();
    }

    private function build()
    {
        $message_sent = false;

        for ($x = 1; $x <= 3; $x++) {
            $reminder_date = $this->invoice->date_to_send;

            if ($this->invoice->customer->getSetting(
                    "enable_reminder{$x}"
                ) === false || $this->invoice->customer->getSetting(
                    "num_days_reminder{$x}"
                ) == 0 || !$reminder_date->isToday()) {
                continue;
            }

            if (!$message_sent) {
                $amount = $this->calculateAmount($x);

                if(empty($amount)) {
                    continue;
                }

                $this->addCharge($amount);

                $this->sendEmail("reminder{$x}");
                
                $this->updateNextReminderDate(
                    $this->invoice->customer->getSetting("schedule_reminder{$x}"),
                    $this->invoice->customer->getSetting("num_days_reminder{$x}")
                );
                $message_sent = true;
            }
        }
    }

    private function calculateAmount($counter)
    { 
        $percentage = $this->invoice->customer->getSetting("late_fee_percent{$counter}");
        
        if(!empty($percentage)) {
            return round($percentage / ($this->invoice->total / 100),2);
        }

        $amount = $this->invoice->customer->getSetting("late_fee_amount{$x}");

        if(empty($amount)) {
            return null;
        }

        return $amount;
    }

    private function addCharge(float $amount)
    {
        $this->invoice->late_fee_charge = $amount;
        $this->invoice_repo->save(['late_fee_charge' => $amount], $this->invoice);
    }

    private function sendEmail($template)
    {
        $subject = $this->invoice->customer->getSetting('email_subject_' . $template);
        $body = $this->invoice->customer->getSetting('email_template_' . $template);
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
