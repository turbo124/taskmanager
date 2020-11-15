<?php

namespace App\Search;

use App\Models\Account;
use App\Models\BankAccount;
use App\Repositories\BankAccountRepository;
use App\Requests\SearchRequest;
use App\Transformations\BankAccountTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BankAccountSearch
 * @package App\Search
 */
class BankAccountSearch extends BaseSearch
{
    use BankAccountTransformable;

    /**
     * @var BankRepository
     */
    private BankRepository $bank_repo;

    private $model;

    /**
     * BankSearch constructor.
     * @param BankRepository $bank_repo
     */
    public function __construct(BankRepository $bank_repo)
    {
        $this->bank_repo = $bank_repo;
        $this->model = $bank_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'created_at' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('banks.*');

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $banks = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->bank_repo->paginateArrayResults($banks, $recordsPerPage);
            return $paginatedResults;
        }

        return $banks;
    }

    public function searchFilter(string $filter = ''): bool
    {
        if (strlen($filter) == 0) {
            return false;
        }

        $this->query->where('banks.name', 'like', '%' . $filter . '%');

        return true;
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $bank_accounts = $list->map(
            function (BankAccount $bank_account) {
                return $this->transformBankAccount($bank_account);
            }
        )->all();

        return $bank_accounts;
    }
}
