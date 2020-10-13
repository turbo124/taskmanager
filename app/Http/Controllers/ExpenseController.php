<?php

namespace App\Http\Controllers;

use App\Factory\ExpenseFactory;
use App\Jobs\Expense\GenerateInvoice;
use App\Models\Expense;
use App\Models\Invoice;
use App\Repositories\ExpenseRepository;
use App\Repositories\InvoiceRepository;
use App\Requests\Expense\CreateExpenseRequest;
use App\Requests\Expense\UpdateExpenseRequest;
use App\Requests\SearchRequest;
use App\Search\ExpenseSearch;
use App\Transformations\ExpenseTransformable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            (new ExpenseSearch($this->expense_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($expenses);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $expense = $this->expense_repo->findExpenseById($id);
        return response()->json($this->transformExpense($expense));
    }

    /**
     * @param UpdateExpenseRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateExpenseRequest $request, int $id)
    {
        $expense = $this->expense_repo->findExpenseById($id);

        $expense = $this->expense_repo->save($request->all(), $expense);

        return response()->json($this->transformExpense($expense->fresh()));
    }

    /**
     * @param CreateExpenseRequest $request
     * @return JsonResponse
     */
    public function store(CreateExpenseRequest $request)
    {
        $expense = $this->expense_repo->createExpense(
            $request->all(),
            ExpenseFactory::create(auth()->user()->account_user()->account, auth()->user())
        );

        return response()->json($this->transformExpense($expense));
    }

    /**
     * @param int $id
     * @return JsonResponse
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

    public function bulk(Request $request)
    {
        $action = $request->action;

        $ids = $request->ids;

        $expenses = Expense::withTrashed()->whereIn('id', $ids)->get();

        if (!$expenses) {
            return response()->json(['message' => "No expense Found"]);
        }

        if ($action === 'create_invoice') {
            GenerateInvoice::dispatchNow(new InvoiceRepository(new Invoice), $expenses);
            return response()->json(['message' => 'The invoice was created successfully!'], 200);
        }

        $responses = [];

        return response()->json($responses);
    }
}
