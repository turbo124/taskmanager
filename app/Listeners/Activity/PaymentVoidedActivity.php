<?php

namespace App\Listeners\Activity;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentVoidedActivity implements ShouldQueue
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
        $fields['data']['id'] = $event->payment->id;
        $fields['data']['message'] = 'A payment was voided';
        $fields['notifiable_id'] = $event->payment->user_id;
        $fields['account_id'] = $event->payment->account_id;
        $fields['notifiable_type'] = get_class($event->payment);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create($event->payment->account_id, $event->payment->user_id);
        $this->notification_repo->save($notification, $fields);
    }
}
