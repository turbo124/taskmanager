<?php

namespace App\Http\Controllers;


use App\Factory\SubscriptionFactory;
use App\Filters\SubscriptionFilters;
use App\Repositories\SubscriptionRepository;
use App\Requests\Subscription\CreateSubscriptionRequest;
use App\Requests\SearchRequest;
use App\Requests\Subscription\UpdateSubscriptionRequest;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    private $subscription_repo;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionRepository $subscription_repo
     */
    public function __construct(SubscriptionRepository $subscription_repo)
    {
        $this->subscription_repo = $subscription_repo;
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $invoices = (new SubscriptionFilters($this->subscription_repo))->filter(
            $request,
            auth()->user()->account_user()->account
        );
        return response()->json($invoices);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $subscription = $this->subscription_repo->findSubscriptionById($id);
        return response()->json($subscription);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(int $id)
    {
        $subscription = $this->subscription_repo->findSubscriptionById($id);
        return response()->json($subscription);
    }

    /**
     * @param int $id
     * @param UpdateSubscriptionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, UpdateSubscriptionRequest $request)
    {
        $subscription = $this->subscription_repo->findSubscriptionById($id);

        $subscription = $this->subscription_repo->save($request->all(), $subscription);

        return response()->json($subscription->fresh());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $subscription = SubscriptionFactory::create(auth()->user()->company()->id, auth()->user()->id);

        return response()->json($subscription);
    }

    /**
     * @param CreateSubscriptionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateSubscriptionRequest $request)
    {
        $subscription = SubscriptionFactory::create(auth()->user()->account_user()->account, auth()->user());
        $this->subscription_repo->save($request->all(), $subscription);
        return response()->json($subscription);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $subscription = $this->subscription_repo->findSubscriptionById($id);
        //may not need these destroy routes as we are using actions to 'archive/delete'
        $subscription->delete();

        return response()->json($subscription);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');
        $subscriptions = Subscription::withTrashed()->find($ids);

        return response()->json($subscriptions);
    }


    /**
     * Store a newly created resource in storage.
     *
     */
    public function subscribe(StoreSubscriptionRequest $request)
    {
        $event_id = $request->input('event_id');
        $target_url = $request->input('target_url');

        if (!in_array($event_id, Subscription::$valid_events)) {
            return response()->json("Invalid event", 400);
        }

        $subscription = new Subscription;
        $subscription->company_id = auth()->user()->company()->id;
        $subscription->user_id = auth()->user()->id;
        $subscription->event_id = $event_id;
        $subscription->target_url = $target_url;
        $subscription->save();

        if (!$subscription->id) {
            return response()->json('Failed to create subscription', 400);
        }

        return response()->json($subscription);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     *
     *
     */
    public function unsubscribe(DestroySubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->delete();

        return response()->json($subscription);
    }
}
