<?php

namespace App\Listeners\Invoice;

use App\Models\Activity;
use App\Models\ClientContact;
use App\Models\InvoiceInvitation;
use App\Repositories\ActivityRepository;
use App\Utils\Traits\MakesHash;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class InvoiceReversedActivity implements ShouldQueue
{
    protected $activity_repo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ActivityRepository $activity_repo)
    {
        $this->activity_repo = $activity_repo;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $fields = new \stdClass;

        $fields->invoice_id = $event->invoice->id;
        $fields->user_id = $event->invoice->user_id;
        $fields->company_id = $event->invoice->company_id;
        $fields->activity_type_id = Activity::REVERSED_INVOICE;

        $this->activity_repo->save($fields, $event->invoice);
    }
}
