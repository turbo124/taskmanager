<?php

namespace App\Listeners\Payment;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentUpdatedActivity implements ShouldQueue
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
        $payment = $event->payment;

        $invoices = $payment->invoices;

        $fields = [];
        $fields['data']['id'] = $payment->id;
        $fields['data']['message'] = 'A payment was updated';
        $fields['notifiable_id'] = $payment->user_id;
        $fields['account_id'] = $payment->account_id;
        $fields['notifiable_type'] = get_class($payment);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create($payment->account_id, $payment->user_id);
        $notification->entity_id = $event->payment->id;
        $this->notification_repo->save($notification, $
    }
}
