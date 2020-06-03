<?php

namespace App\Http\Controllers;


use App\Cases;
use App\Customer;
use App\Factory\CaseFactory;
use App\Filters\CaseFilter;
use App\Repositories\CaseRepository;
use App\Requests\Cases\CreateCaseRequest;
use App\Requests\Cases\UpdateCaseRequest;
use App\Requests\SearchRequest;
use App\Transformations\CaseTransformable;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    use CaseTransformable;

    /**
     * @var CaseRepository
     */
    private CaseRepository $case_repo;

    /**
     * CaseController constructor.
     * @param CaseRepository $case_repository
     */
    public function __construct(CaseRepository $case_repository)
    {
        $this->case_repo = $case_repository;
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $cases = (new CaseFilter($this->case_repo))->filter(
            $request,
            auth()->user()->account_user()->account
        );
        return response()->json($cases);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $case = $this->case_repo->findCaseById($id);
        return response()->json($case);
    }

    /**
     * @param int $id
     * @param UpdateCaseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, UpdateCaseRequest $request)
    {
        $case = $this->case_repo->findCaseById($id);
        $case = $this->case_repo->save($request->all(), $case);
        return response()->json($this->transform($case->fresh()));
    }

    /**
     * @param CreateCaseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCaseRequest $request)
    {
        $case = CaseFactory::create(auth()->user()->account_user()->account, auth()->user(), Customer::find($request->customer_id)->first());
        $this->case_repo->save($request->all(), $case);
        return response()->json($this->transform($case));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $case = $this->case_repo->findCaseById($id);
        //may not need these destroy routes as we are using actions to 'archive/delete'
        $case->delete();

        return response()->json($case);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');
        $cases = Cases::withTrashed()->find($ids);

        return response()->json($cases);
    }
}
