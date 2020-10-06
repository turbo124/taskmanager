<?php

namespace App\Repositories;

use App\Jobs\Expense\GenerateInvoice;
use App\Models\Expense;
use App\Models\Invoice;
use App\Repositories\Base\BaseRepository;

/**
 * ExpenseRepository
 */
class ExpenseRepository extends BaseRepository
{

    /**
     * ExpenseRepository constructor.
     * @param Expense $expense
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
     * @return Expense
     */
    public function findExpenseById(int $id): Expense
    {
        return $this->findOneOrFail($id);
    }

    public function createExpense(array $data, Expense $expense): ?Expense
    {
        $expense = $this->save($data, $expense);

        if (!empty($data['create_invoice']) && $data['create_invoice'] === true) {
            GenerateInvoice::dispatchNow(new InvoiceRepository(new Invoice), collect([$expense]), $data);
        }

        return $expense;
    }

    public function save(array $data, Expense $expense): ?Expense
    {
        $expense->fill($data);
        $expense->setNumber();
        $expense->save();

        return $expense;
    }
}
