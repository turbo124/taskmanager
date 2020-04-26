<?php

namespace App\Listeners\Credit;

use App\Factory\NotificationFactory;
use App\Libraries\MultiDB;
use App\Models\Activity;
use App\Models\ClientContact;
use App\Models\InvoiceInvitation;
use App\Repositories\ActivityRepository;
use App\Repositories\NotificationRepository;
use App\Utils\Traits\MakesHash;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreditDeletedActivity implements ShouldQueue
{
    protected $notification_repo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(NotificationRepository $notification_repo)
    {
        $this->notification_repo = $notification_repo;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $fields = [];
        $fields['data']['id'] = $event->credit->id;
        $fields['data']['message'] = 'A credit was deleted';
        $fields['notifiable_id'] = $event->credit->user_id;
        $fields['account_id'] = $event->credit->account_id;
        $fields['notifiable_type'] = get_class($event->credit);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create($event->credit->account_id, $event->credit->user_id);
        $this->notification_repo->save($notification, $fields);
    }
}
