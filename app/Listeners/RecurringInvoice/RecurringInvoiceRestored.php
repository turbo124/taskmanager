<?php

namespace App\Listeners\RecurringInvoice;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecurringInvoiceRestored implements ShouldQueue
{
    /**
     * @var NotificationRepository
     */
    protected NotificationRepository $notification_repo;

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
        $fields['data']['id'] = $event->recurringInvoice->id;
        $fields['data']['customer_id'] = $event->recurringInvoice->customer_id;
        $fields['data']['message'] = 'A recurringInvoice was restored';
        $fields['notifiable_id'] = $event->recurringInvoice->user_id;
        $fields['account_id'] = $event->recurringInvoice->account_id;
        $fields['notifiable_type'] = get_class($event->recurringInvoice);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create($event->recurringInvoice->account_id, $event->recurringInvoice->user_id);
        $notification->entity_id = $event->recurringInvoice->id;
        $this->notification_repo->save($notification, $fields);
    }
}
