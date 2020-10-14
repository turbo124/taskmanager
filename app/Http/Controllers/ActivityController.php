<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\UserRepository;
use App\Requests\SearchRequest;
use App\Search\CustomerSearch;
use App\Search\UserSearch;
use App\Transformations\EventTransformable;
use App\Transformations\NotificationTransformable;

class ActivityController extends Controller
{

    use NotificationTransformable, EventTransformable;

    /**
     * @var CommentRepositoryInterface
     */
    private $comment_repo;

    /**
     * @var NotificationRepositoryInterface
     */
    private $notification_repo;

    /**
     * @var EventRepositoryInterface
     */
    private $event_repo;

    /**
     * ActivityController constructor.
     *
     * @param CommentRepositoryInterface $commentRepository
     * NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(
        CommentRepositoryInterface $comment_repo,
        NotificationRepositoryInterface $notification_repo,
        EventRepositoryInterface $event_repo
    ) {
        $this->comment_repo = $comment_repo;
        $this->notification_repo = $notification_repo;
        $this->event_repo = $event_repo;
    }

    public function index()
    {
        $currentUser = auth()->user();
        $comments = auth()->user()->account_user()->account->comments()->with('user')->get();
        $list = $this->notification_repo->listNotifications('*', 'created_at', 'DESC');
        $userEvents = $this->event_repo->getEventsForUser($currentUser, auth()->user()->account_user()->account_id);

        $events = $userEvents->map(
            function (Event $event) {
                return $this->transformEvent($event);
            }
        )->all();

        $notifications = $list->map(
            function (Notification $notification) {
                return $this->transformNotification($notification);
            }
        )->all();

        return response()->json(
            [
                'users'         => (new UserSearch(new UserRepository(new User())))->filter(
                    new SearchRequest(),
                    auth()->user()->account_user()->account
                ),
                'customers'     => (new CustomerSearch(new CustomerRepository(new Customer())))->filter(
                    new SearchRequest(),
                    auth()->user()->account_user()->account
                ),
                'notifications' => $notifications,
                'comments'      => $comments,
                'events'        => $events
            ]
        );
    }

}
