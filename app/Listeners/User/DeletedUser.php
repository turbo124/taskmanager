<?php

namespace App\Listeners\User;

use App\Factory\NotificationFactory;
use App\Repositories\NotificationRepository;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletedUser implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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

        if (auth()->user()->id) {
            $fields['data']['id'] = auth()->user()->id;
            $fields['notifiable_id'] = auth()->user()->id;
        } else {
            $fields['data']['id'] = $event->user->id;
            $fields['notifiable_id'] = $event->user->id;
        }

        $fields['data']['message'] = 'A user was deleted';
        $fields['account_id'] = $event->user->account_id;
        $fields['notifiable_type'] = get_class($event->user);
        $fields['type'] = get_class($this);
        $fields['data'] = json_encode($fields['data']);

        $notification = NotificationFactory::create($event->user->account_id, $event->user->user_id);
        $notification->entity_id = $event->user->id;
        $this->notification_repo->save($notification, $fields);
    }
}
