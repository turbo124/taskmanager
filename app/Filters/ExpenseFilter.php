<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\Company;
use App\Models\Expense;
use App\Repositories\CompanyRepository;
use App\Repositories\ExpenseRepository;
use App\Requests\SearchRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Transformations\ExpenseTransformable;

/**
 * ExpenseFilters
 */
class ExpenseFilter extends QueryFilter
{
    use ExpenseTransformable;

    private $expense_repo;

    /**
     * CompanyFilter constructor.
     * @param CompanyRepository $companyRepository
     */
    public function __construct(ExpenseRepository $expense_repo)
    {
        $this->expense_repo = $expense_repo;
        $this->model = $expense_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param \App\Models\Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'amount' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->has('status')) {
            $this->status('expenses', $request->status);
        }

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->filled('customer_id')) {
            $this->query->whereCustomerId($request->customer_id);
        }

        if ($request->filled('company_id')) {
            $this->query->whereCompanyId($request->company_id);
        }

        if ($request->filled('category_id')) {
            $this->query->whereCategoryId($request->category_id);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $expenses = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->expense_repo->paginateArrayResults($expenses, $recordsPerPage);
            return $paginatedResults;
        }

        return $expenses;
    }


    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $expenses = $list->map(
            function (Expense $expense) {
                return $this->transformExpense($expense);
            }
        )->all();

        return $expenses;
    }

    /**
     * Filter based on search text
     *
     * @param string query filter
     * @return Illuminate\Database\Query\Builder
     * @deprecated
     *
     */
    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return false;
        }

        return $this->query->where(
            function ($query) use ($filter) {
                $query->where('expenses.name', 'like', '%' . $filter . '%')
                      ->orWhere('expenses.number', 'like', '%' . $filter . '%')
                    //->orWhere('expense_contacts.first_name', 'like', '%'.$filter.'%')
                    //->orWhere('expense_contacts.last_name', 'like', '%'.$filter.'%')
                    //->orWhere('expense_contacts.email', 'like', '%'.$filter.'%')
                      ->orWhere('expenses.custom_value1', 'like', '%' . $filter . '%')
                      ->orWhere('expenses.custom_value2', 'like', '%' . $filter . '%')
                      ->orWhere('expenses.custom_value3', 'like', '%' . $filter . '%')
                      ->orWhere('expenses.custom_value4', 'like', '%' . $filter . '%');
            }
        );
    }
}
