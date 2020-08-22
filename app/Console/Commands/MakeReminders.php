<?php

namespace App\Console\Commands;

use App\Jobs\Invoice\SendReminders;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Illuminate\Console\Command;

class MakeReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        SendReminders::dispatchNow((new InvoiceRepository(new Invoice)));
    }
}
