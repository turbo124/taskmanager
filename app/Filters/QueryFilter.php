<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\Invoice;
use Carbon\Carbon;

class QueryFilter
{

    protected function filterDates($request)
    {
        $start = date("Y-m-d", strtotime($request->input('start_date')));
        $end = date("Y-m-d", strtotime($request->input('end_date')));
        $this->query->whereBetween('created_at', [$start, $end]);
    }

    protected function orderBy($orderBy, $orderDir)
    {
        $this->query->orderBy($orderBy, $orderDir);
    }

    /**
     * @param \App\Models\Account $account
     * @param string $table
     */
    protected function addAccount(Account $account, $table = '')
    {
        $field = !empty($table) ? $table . '.account_id' : 'account_id';
        $this->query->where($field, '=', $account->id);
    }

    /**
     * Filters the list based on the status
     * archived, active, deleted
     * @param string $table
     * @param string $filter
     * @return mixed
     */
    protected function status(string $table, string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        if (is_numeric($filter)) {
            $this->query->where($table . '.status_id', '=', (int)$filter);
        }

        if ($filter === 'invoice_overdue') {
            $this->query->whereIn(
                'status_id',
                [
                    Invoice::STATUS_SENT,
                    Invoice::STATUS_PARTIAL
                ]
            )->where('due_date', '<', Carbon::now())->orWhere('partial_due_date', '<', Carbon::now());
        }

        if ($filter === 'active') {
            $this->query->whereNull($table . '.deleted_at');
        }

        if ($filter === 'archived') {
            $this->query->whereNotNull($table . '.deleted_at')->withTrashed();
        }

        if ($filter === 'deleted') {
            $this->query->where($table . '.is_deleted', '=', 1)->withTrashed();
        }
    }
}
