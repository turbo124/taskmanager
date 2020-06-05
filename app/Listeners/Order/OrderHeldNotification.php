<?php

namespace App\Listeners\Order;

use App\Notifications\Admin\OrderHeld;
use App\Traits\Notifications\UserNotifies;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;

class OrderHeldNotification implements ShouldQueue
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
        $order = $event->order;

        if (!empty($order->account->account_users)) {
            foreach ($order->account->account_users as $account_user) {
                $notification_types = $this->getNotificationTypesForAccountUser(
                    $account_user,
                    ['order_held']
                );

                if (!empty($notification_types) && in_array('mail', $notification_types)) {
                    $account_user->user->notify(new OrderHeld($order, 'mail'));
                }
            }
        }

        if (isset($order->account->slack_webhook_url)) {
            Notification::route('slack', $order->account->slack_webhook_url)->notify(
                new OrderHeld(
                    $order,
                    'slack'
                )
            );
        }
    }
}
