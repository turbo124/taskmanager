<?php

namespace App\Services\Invoice;

use App\Helpers\InvoiceCalculator\LineItem;
use App\Models\Invoice;
use Carbon\Carbon;

class SendReminders
{
    private $settings;

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * SendReminders constructor.
     * @param $settings
     * @param \App\Models\Invoice $invoice
     */
    public function __construct($settings, Invoice $invoice)
    {
        $this->settings = $settings;
        $this->invoice = $invoice;
    }

    /**
     * @param $reminder_type
     * @param $number_of_days
     * @return bool
     */
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

        $this->invoice->next_send_date = $next_send_date;
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

        // update total
        $this->invoice->total += $amount;

        // create line
        $line_items = $this->invoice->line_items;
        $line_items[] = (new LineItem)
            ->setQuantity(1)
            ->setNotes('Late fee for invoice')
            ->setUnitPrice($amount)
            ->setSubTotal($amount)
            ->toObject();

        $this->invoice->line_items = $line_items;
    }

    public function execute()
    {
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
}
