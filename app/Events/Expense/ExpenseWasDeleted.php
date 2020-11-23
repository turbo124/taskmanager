<?php

namespace App\Events\Expense;

use App\Models\Expense;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class ExpenseWasDeleted
{
    use SerializesModels;

    /**
     * @var Expense
     */
    public Expense $expense;

    /**
     * ExpenseWasDeleted constructor.
     * @param Expense $expense
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }
}
