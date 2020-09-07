<?php

namespace App\Listeners\Credit;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreditEmailActivity implements ShouldQueue
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
        $fields['data']['id'] = $event->invitation->credit->id;
        $fields['data']['customer_id'] = $event->invitation->credit->customer_id;
        $fields['data']['message'] = 'An credit was emailed';
        $fields['data']['contact_id'] = $event->invitation->credit->contact_id;
        $fields['notifiable_id'] = $event->invitation->credit->user_id;
        $fields['account_id'] = $event->invitation->credit->account_id;
        $fields['notifiable_type'] = get_class($event->invitation->credit);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification =
            NotificationFactory::create($event->invitation->credit->account_id, $event->invitation->credit->user_id);
        $notification->entity_id = $event->invitation->credit->id;
        $this->notification_repo->save($notification, $fields);
    }
}
