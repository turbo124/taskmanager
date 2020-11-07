<?php

namespace App\Events\Company;

use App\Models\Company;
use App\Models\Expense;
use App\Models\RecurringInvoice;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class ExpenseWasCreated
 * @package App\Events\RecurringInvoice
 */
class CompanyWasCreated
{
    use SerializesModels;
    use SendSubscription;

    /**
     * @var Company
     */
    public Company $company;

    /**
     * ExpenseWasCreated constructor.
     * @param Expense $expense
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->send($company, get_class($this));
    }
}