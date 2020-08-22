<?php

namespace App\Http\Controllers;

use App\Factory\DealFactory;
use App\Filters\DealFilter;
use App\Models\Deal;
use App\Repositories\CustomerRepository;
use App\Repositories\DealRepository;
use App\Requests\SearchRequest;
use App\Requests\Deal\CreateDealRequest;
use App\Requests\Deal\UpdateDealRequest;
use App\Transformations\DealTransformable;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{

    use DealTransformable;

    /**
     * @var DealRepository
     */
    private $deal_repo;

    private $task_service;

    /**
     *
     * @param DealRepository $dealRepository
     */
    public function __construct(DealRepository $deal_repo)
    {
        $this->deal_repo = $deal_repo;
        
    }

    public function index(SearchRequest $request)
    {
        $deals = (new DealFilter($this->deal_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($deals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateDealRequest $request)
    {
        $deal = $this->deal_repo->save(
            $request->all(),
            (new DealFactory)->create(auth()->user(), auth()->user()->account_user()->account)
        );

        //$task = SaveTaskTimes::dispatchNow($request->all(), $task);
        return response()->json($this->transformDeal($deal));
    }

    /**
     *
     * @param int $task_id
     * @return type
     */
    public function markAsCompleted(int $deal_id)
    {
        $objDeal = $this->deal_repo->findDealById(deal_id);
        $deal = $this->deal_repo->save(['is_completed' => true], $deal);
        return response()->json($deal);
    }

    
    /**
     * @param UpdateDealRequest $request
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateDealRequest $request, int $id)
    {
        $deal = $this->deal_repo->findDealById($id);
        $deal = $this->deal_repo->save($request->all(), $deal);
       

        return response()->json($deal);
    }

    public function show(int $id)
    {
        $deal = $this->deal_repo->getDealById($id);
        return response()->json($this->transformDeal($deal));
    }


    /**
     * @param $id
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function archive(int $id)
    {
        $deal = $this->deal_repo->findDealById($id);
        $deal->delete();
    }

    public function destroy(int $id)
    {
        $deal = $this->deal_repo->findDealById($id);
        $this->deal_repo->newDelete($deal);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $deal = Deal::withTrashed()->where('id', '=', $id)->first();
        $this->deal_repo->restore($deal);
        return response()->json([], 200);
    }
}
