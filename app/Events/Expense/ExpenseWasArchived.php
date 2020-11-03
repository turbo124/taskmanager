<?php

namespace App\Events\Expense;

use App\Models\Cases;
use App\Models\Company;
use App\Models\Expense;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class ExpenseWasArchived
{
    use SerializesModels;

    /**
     * @var Expense
     */
    public Expense $expense;

    /**
     * ExpenseWasArchived constructor.
     * @param Expense $expense
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }
}
