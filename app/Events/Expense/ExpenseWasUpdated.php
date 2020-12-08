<?php

namespace App\Events\Expense;

use App\Models\Expense;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class ExpenseWasUpdated
{
    use SerializesModels;

    /**
     * @var Expense
     */
    public Expense $expense;

    /**
     * ExpenseWasUpdated constructor.
     * @param Expense $expense
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
        $this->send($expense, get_class($this));
    }
}
