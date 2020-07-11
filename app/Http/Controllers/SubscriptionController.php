<?php

namespace App\Http\Controllers;


use App\Factory\SubscriptionFactory;
use App\Filters\SubscriptionFilters;
use App\Repositories\SubscriptionRepository;
use App\Requests\SearchRequest;
use App\Requests\Subscription\CreateSubscriptionRequest;
use App\Requests\Subscription\UpdateSubscriptionRequest;
use App\Subscription;
use App\Transformations\SubscriptionTransformable;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use SubscriptionTransformable;

    /**
     * @var SubscriptionRepository
     */
    private SubscriptionRepository $subscription_repo;

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
        $subscriptions = (new SubscriptionFilters($this->subscription_repo))->filter(
            $request,
            auth()->user()->account_user()->account
        );
        return response()->json($subscriptions);
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
     * @param UpdateSubscriptionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, UpdateSubscriptionRequest $request)
    {
        $subscription = $this->subscription_repo->findSubscriptionById($id);

        $subscription = $this->subscription_repo->save($request->all(), $subscription);

        return response()->json($this->transform($subscription));
    }

    /**
     * @param CreateSubscriptionRequest $request\
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateSubscriptionRequest $request)
    {
        $subscription = SubscriptionFactory::create(auth()->user()->account_user()->account, auth()->user());
        $subscription = $this->subscription_repo->save($request->all(), $subscription);
        return response()->json($this->transform($subscription));
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
}
