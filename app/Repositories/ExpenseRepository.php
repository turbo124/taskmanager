<?php

namespace App\Repositories;

use App\Models\Company;
use App\Factory\ExpenseFactory;
use App\Models\Expense;
use App\Repositories\Base\BaseRepository;
use Illuminate\Http\Request;

/**
 * ExpenseRepository
 */
class ExpenseRepository extends BaseRepository
{

    /**
     * ExpenseRepository constructor.
     * @param \App\Models\Expense $expense
     */
    public function __construct(Expense $expense)
    {
        parent::__construct($expense);
        $this->model = $expense;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     * @return \App\Models\Expense
     */
    public function findExpenseById(int $id): Expense
    {
        return $this->findOneOrFail($id);
    }

    public function save(array $data, Expense $expense): ?Expense
    {
        $expense->fill($data);
        $expense->setNumber();
        $expense->save();

        return $expense;
    }
}
