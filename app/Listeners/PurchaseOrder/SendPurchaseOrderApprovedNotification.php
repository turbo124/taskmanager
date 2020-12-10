<?php


namespace App\Listeners\PurchaseOrder;


use App\Notifications\Admin\PurchaseOrderApprovedNotification;
use App\Notifications\Admin\QuoteApprovedNotification;
use App\Traits\Notifications\UserNotifies;
use Illuminate\Support\Facades\Notification;

class SendPurchaseOrderApprovedNotification
{
    use UserNotifies;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $purchase_order = $event->purchase_order;

        if (!empty($purchase_order->account->account_users)) {
            foreach ($purchase_order->account->account_users as $account_user) {
                $notification_types = $this->getNotificationTypesForAccountUser(
                    $account_user,
                    ['quote_approved']
                );

                if (!empty($notification_types) && in_array('mail', $notification_types)) {
                    $account_user->user->notify(new PurchaseOrderApprovedNotification($purchase_order, 'mail'));
                }
            }
        }

        if (isset($purchase_order->account->slack_webhook_url)) {
            Notification::route('slack', $purchase_order->account->slack_webhook_url)->notify(
                new PurchaseOrderApprovedNotification(
                    $purchase_order,
                    'slack'
                )
            );
        }
    }
}