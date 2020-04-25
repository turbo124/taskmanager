<?php

namespace App\Listeners\Invoice;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceMarkedSentActivity implements ShouldQueue
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
        $fields['data']['id'] = $event->invoice->id;
        $fields['data']['message'] = 'An invoice was marked sent';
        $fields['notifiable_id'] = $event->invoice->user_id;
        $fields['account_id'] = $event->invoice->account_id;
        $fields['notifiable_type'] = get_class($event->invoice);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create($event->invoice->account_id, $event->invoice->user_id);
        $this->notification_repo->save($notification, $fields);

        //$this->notification_repo->create($fields);
    }
}
