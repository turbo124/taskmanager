<?php


namespace App\Traits\Notifications;

trait UserNotifies
{

    public function findUserNotificationTypes($invitation, $account_user, $entity_name, $required_permissions): array
    {
        $notifiable_methods = [];

        $notifications = json_decode(json_encode($account_user->notifications->email), true);

        if (empty($notifications)) {
            return $notifiable_methods;
        }

        $found = array_filter($notifications, function ($v, $k) {
            return $v['isChecked'] == 1;
        }, ARRAY_FILTER_USE_BOTH);

        $keys = array_column($found, 'value');

        if ($invitation->{$entity_name}->user_id == $account_user->_user_id ||
            $invitation->{$entity_name}->assigned_user_id == $account_user->user_id) {
            array_push($required_permissions, "all_user_notifications");
        }

        if (count(array_intersect($required_permissions, $keys)) >= 1) {
            array_push($notifiable_methods, 'mail');
        }

        // if(count(array_intersect($required_permissions, $notifications->slack)) >=1)
        //     array_push($notifiable_methods, 'slack');

        return $notifiable_methods;

    }

}
