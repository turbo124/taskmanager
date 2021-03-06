<?php

namespace App\Listeners\Lead;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeadDeleted implements ShouldQueue
{
    /**
     * @var NotificationRepository
     */
    protected NotificationRepository $notification_repo;

    /**
     * Create the event listener.
     *
     * @param NotificationRepository $notification_repo
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
        $fields['data']['id'] = $event->lead->id;
        $fields['data']['customer_id'] = $event->lead->customer_id;
        $fields['data']['message'] = 'A lead was deleted';
        $fields['notifiable_id'] = $event->lead->user_id;
        $fields['account_id'] = $event->lead->account_id;
        $fields['notifiable_type'] = get_class($event->lead);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create($event->lead->account_id, $event->lead->user_id);
        $notification->entity_id = $event->lead->id;
        $this->notification_repo->save($notification, $fields);
    }
}
