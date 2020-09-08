<?php

namespace App\Http\Controllers;


use App\Factory\CaseFactory;
use App\Filters\CaseFilter;
use App\Models\Cases;
use App\Models\CompanyToken;
use App\Models\Customer;
use App\Repositories\CaseRepository;
use App\Requests\Cases\CreateCaseRequest;
use App\Requests\Cases\UpdateCaseRequest;
use App\Requests\SearchRequest;
use App\Transformations\CaseTransformable;
use Exception;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Log;

use function request;

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
     * @return JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $token_sent = request()->bearerToken();
        $token = CompanyToken::whereToken($token_sent)->first();
        $account = $token->account;

        $cases = (new CaseFilter($this->case_repo))->filter(
            $request,
            $account
        );
        return response()->json($cases);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $case = $this->case_repo->findCaseById($id);
        return response()->json($this->transform($case));
    }

    /**
     * @param int $id
     * @param UpdateCaseRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateCaseRequest $request)
    {
        $case = $this->case_repo->findCaseById($id);
        $case = $this->case_repo->updateCase($request->all(), $case);
        return response()->json($this->transform($case->fresh()));
    }

    /**
     * @param CreateCaseRequest $request
     * @return JsonResponse
     */
    public function store(CreateCaseRequest $request)
    {
        $token_sent = request()->bearerToken();
        $token = CompanyToken::whereToken($token_sent)->first();
        $account = $token->account;
        $user = $token->user;

        $case = CaseFactory::create(
            $account,
            $user,
            Customer::find($request->customer_id)->first()
        );

        Log::emergency($request->all());

        $this->case_repo->createCase($request->all(), $case);
        return response()->json($this->transform($case));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $id)
    {
        $case = $this->case_repo->findCaseById($id);
        //may not need these destroy routes as we are using actions to 'archive/delete'
        $case->delete();

        return response()->json($case);
    }

    /**
     * @return JsonResponse
     */
    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');
        $cases = Cases::withTrashed()->find($ids);

        return response()->json($cases);
    }
}
