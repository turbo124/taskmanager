<?php

namespace App\Repositories;

use App\Filters\DealFilter;
use App\Models\Account;
use App\Models\Project;
use App\Models\Deal;
use App\Models\User;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Requests\SearchRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as Support;
use Illuminate\Support\Facades\DB;

class DealRepository extends BaseRepository implements TaskRepositoryInterface
{
   

    /**
     * DealRepository constructor.
     *
     * @param Deal $deal
     */
    public function __construct(Deal $deal)
    {
        parent::__construct($deal);
        $this->model = $deal;
    }

    /**
     * @param int $id
     *
     * @return Task
     * @throws Exception
     */
    public function findDealById(int $id): Deal
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleteDeal(): bool
    {
        return $this->delete();
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new DealFilter($this))->filter($search_request, $account);
    }

    public function getDeals($limit = null, User $objUser = null): Support
    {
        $query = $this->model->orderBy('deals.created_at', 'desc');

        if ($objUser !== null) {
            $query->where('assigned_to', '=', $objUser->id);
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getModel()
    {
        return $this->model;
    }

   

    /**
     *
     * @return type
     */
    public function getSourceTypeCounts(int $task_type, $account_id): Support
    {
        return $this->model->join('source_type', 'source_type.id', '=', 'deals.source_type')
                           ->select('source_type.name', DB::raw('count(*) as value'))
                           ->where('deals.account_id', $account_id)->groupBy('source_type.name')->get();
    }

    /**
     *
     * @return type
     */
    public function getStatusCounts(int $task_type, int $account_id): Support
    {
        return $this->model->join('task_statuses', 'task_statuses.id', '=', 'tasks.task_status')
                           ->select(
                               'task_statuses.name',
                               DB::raw('CEILING(count(*) * 100 / (select count(*) from tasks)) as value')
                           )
                           ->where('tasks.task_type', $task_type)->where('tasks.account_id', $account_id)
                           ->groupBy('task_statuses.name')->get();
    }

    /**
     *
     * @param int $task_type
     * @param int $number_of_days
     * @return type
     */
    public function getRecentTasks(int $task_type, int $number_of_days, int $account_id)
    {
        $date = Carbon::today()->subDays($number_of_days);
        $result = $this->model->select(DB::raw('count(*) as total'))->where('created_at', '>=', $date)
                              ->where('account_id', $account_id)->get();

        return !empty($result[0]) ? $result[0]['total'] : 0;
    }

    /**
     *
     * @param int $task_type
     * @return type
     */
    public function getNewDeals(int $task_type, int $account_id)
    {
        $result = $this->model->select(DB::raw('count(*) as total'))
                              ->where('account_id', $account_id)->get();

        return !empty($result[0]) ? $result[0]['total'] : 0;
    }

    /**
     *
     * @param int $task_type
     * @return type
     */
    public function getTotalEarnt(int $task_type, int $account_id)
    {
        return $this->model->where('task_type', $task_type)->where('account_id', $account_id)->sum('valued_at');
    }

    
    /**
     * @param $data
     * @param Task $task
     * @return Task|null
     * @throws Exception
     */
    public function save($data, Deal $deal): ?Deal
    {
        
        $data['source_type'] = empty($data['source_type']) ? 1 : $data['source_type'];

        $deal->fill($data);
        $deal->save();

        return $deal->fresh();
    }

}