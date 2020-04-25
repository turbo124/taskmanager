<?php

namespace App\Jobs\Invoice;

use App\Invoice;
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

        $invoices->each(function ($invoice) {
            $invoice->service()->sendReminders();
        });

    }
}
