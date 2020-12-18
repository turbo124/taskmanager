<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceOverdue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $client
     * @param $invoice
     */
    public function __construct($client, $invoice)
    {
        $this->client = $client;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $diffInDays = $this->invoice->due_date->diffInDays();
        return $this->view('emails.invoices.overdue')
                    ->with('client', $this->client)
                    ->with('invoice', $this->invoice)
                    ->with('overdueDays', $diffInDays)
                    ->subject('[' . $this->client->name . '] Invoice #' . $this->invoice->number . ' Is Now Overdue')
                    ->to($this->client->email, $this->client->name);
    }
}
