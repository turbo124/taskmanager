<?php

namespace App\Http\Controllers;

use App\Factory\ExpenseFactory;
use App\Filters\ExpenseFilter;
use App\Requests\Expense\CreateExpenseRequest;
use App\Requests\Expense\UpdateExpenseRequest;
use App\Requests\SearchRequest;
use App\Expense;
use App\Repositories\ExpenseRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Transformations\ExpenseTransformable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Class ExpenseController
 * @package App\Http\Controllers
 */
class ExpenseController extends Controller
{
    use ExpenseTransformable;

    /**
     * @var
     */
    protected $expense_repo;

    /**
     * ExpenseController constructor.
     * @param ExpenseRepository $expense_repo
     */
    public function __construct(ExpenseRepository $expense_repo)
    {
        $this->expense_repo = $expense_repo;
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        $expenses =
            (new ExpenseFilter($this->expense_repo))->filter($request, auth()->user()->account_user()->account_id);
        return response()->json($expenses);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $expense = $this->expense_repo->findExpenseById($id);
        return response()->json($this->transformExpense($expense));
    }

    /**
     * @param UpdateExpenseRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateExpenseRequest $request, int $id)
    {
        $expense = $this->expense_repo->findExpenseById($id);

        $expense = $this->expense_repo->save($request->all(), $expense);

        return response()->json($this->transformExpense($expense->fresh()));
    }

    /**
     * @param CreateExpenseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateExpenseRequest $request)
    {
        $expense = $this->expense_repo->save($request->all(),
            ExpenseFactory::create(auth()->user()->account_user()->id, auth()->user()->account_user()->account_id));

        return response()->json($this->transformExpense($expense));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $expense = Expense::withTrashed()->where('id', '=', $id)->first();
        $this->expense_repo->newDelete($expense);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = Expense::withTrashed()->where('id', '=', $id)->first();
        $this->expense_repo->restore($group);
        return response()->json([], 200);
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function archive(int $id)
    {
        $expense = $this->expense_repo->findExpenseById($id);
        $expenseRepo = new ExpenseRepository($expense);
        $expenseRepo->archive($expense);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');
        $expenses = Expense::withTrashed()->find($ids);

        return response()->json($expenses);
    }
}
