<?php

namespace App\Listeners\PurchaseOrder;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class PurchaseOrderEmailedActivity implements ShouldQueue
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
        $fields['data']['id'] = $event->purchase_order_invitation->inviteable->id;
        $fields['data']['contact_id'] = $event->purchase_order_invitation->contact_id;
        $fields['data']['company_id'] = $event->purchase_order_invitation->inviteable->company_id;
        $fields['data']['message'] = 'A purchase order was emailed';
        $fields['notifiable_id'] = $event->purchase_order_invitation->inviteable->user_id;
        $fields['account_id'] = $event->purchase_order_invitation->inviteable->account_id;
        $fields['notifiable_type'] = get_class($event->purchase_order_invitation->inviteable);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create(
            $event->purchase_order_invitation->inviteable->account_id,
            $event->purchase_order_invitation->inviteable->user_id
        );
        $notification->entity_id = $event->purchase_order_invitation->inviteable->id;
        $this->notification_repo->save($notification, $fields);
    }
}
