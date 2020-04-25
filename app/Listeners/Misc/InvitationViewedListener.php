<?php

namespace App\Listeners\Misc;

use App\Notification;
use App\Notifications\Admin\EntityViewedNotification;
use App\Traits\Notifications\UserNotifies;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvitationViewedListener implements ShouldQueue
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
        $entity_name = $event->entity;
        $invitation = $event->invitation;

        $notification = new EntityViewedNotification($invitation, $entity_name);

        foreach ($invitation->account->account_users as $account_user) {

            $entity_viewed = "{$entity_name}_viewed";

            $notification->method = $this->findUserNotificationTypes($invitation, $account_user, $entity_name,
                ['all_notifications', $entity_viewed]);

            $account_user->user->notify($notification);
        }

        if (isset($invitation->account->slack_webhook_url)) {

            $notification->method = ['slack'];

            Notification::route('slack', $invitation->account->slack_webhook_url)->notify($notification);

        }
    }


    private function userNotificationArray($notifications)
    {
        $via_array = [];

    }

}
