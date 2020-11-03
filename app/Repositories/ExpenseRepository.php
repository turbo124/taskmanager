<?php

namespace App\Repositories;

use App\Events\Expense\ExpenseWasCreated;
use App\Events\Expense\ExpenseWasUpdated;
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

        if (!empty($data['create_invoice']) && $data['create_invoice'] === true && $expense->customer->getSetting(
                'expense_auto_create_invoice'
            ) === true) {
            GenerateInvoice::dispatchNow(new InvoiceRepository(new Invoice), collect([$expense]), $data);
        }

        event(new ExpenseWasCreated($expense));

        return $expense;
    }

    /**
     * @param array $data
     * @param Expense $expense
     * @return Expense|null
     */
    public function save(array $data, Expense $expense): ?Expense
    {
        $is_add = !empty($expense->id);

        $expense->fill($data);
        $expense->setNumber();
        $expense->save();

        if(!$is_add) {
            event(new ExpenseWasUpdated($expense));
        }

        return $expense;
    }
}
