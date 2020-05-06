<?php

namespace App\Filters;

class QueryFilter
{

    /**
     * active status
     */
    const STATUS_ACTIVE = 'active';
    /**
     * archived status
     */
    const STATUS_ARCHIVED = 'archived';
    /**
     * deleted status
     */
    const STATUS_DELETED = 'deleted';

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

    protected function addAccount(int $account_id, $table = '')
    {
        $field = !empty($table) ? $table . '.account_id' : 'account_id';
        $this->query->where($field, '=', $account_id);
    }

    /**
     * Filters the list based on the status
     * archived, active, deleted
     *
     * @param string filter
     * @return Illuminate\Database\Query\Builder
     */
    protected function status(string $table, string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        $filters = explode(',', $filter);

        $this->query->whereNull($table . '.id');
        if (in_array(self::STATUS_ACTIVE, $filters)) {
            $this->query->orWhereNull($table . '.deleted_at');
        }

        if (in_array(self::STATUS_ARCHIVED, $filters)) {
            $this->query->orWhere(function ($query) use ($table) {
                $query->whereNotNull($table . '.deleted_at');
            });

            $this->query->withTrashed();
        }
        if (in_array(self::STATUS_DELETED, $filters)) {
            $this->query->orWhere($table . '.is_deleted', '=', 1)->withTrashed();
        }
    }
}
