<?php

namespace App\Listeners\quote;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuoteEmailedActivity implements ShouldQueue
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
        $fields['data']['id'] = $event->invitation->quote->id;
        $fields['data']['customer_id'] = $event->invitation->quote->customer_id;
        $fields['data']['message'] = 'An quote was emailed';
        $fields['data']['client_contact_id'] = $event->invitation->quote->client_contact_id;
        $fields['notifiable_id'] = $event->invitation->quote->user_id;
        $fields['account_id'] = $event->invitation->quote->account_id;
        $fields['notifiable_type'] = get_class($event->invitation->quote);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification =
            NotificationFactory::create($event->invitation->quote->account_id, $event->invitation->quote->user_id);
        $notification->entity_id = $event->invitation->quote->id;
        $this->notification_repo->save($notification, $fields);
    }
}
