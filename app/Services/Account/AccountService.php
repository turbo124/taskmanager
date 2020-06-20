<?php

namespace App\Services\Account;

use App\Account;
use App\Lead;
use App\Services\Account\ConvertAccount;
use App\Services\ServiceBase;

/**
 * Class TaskService
 * @package App\Services\Task
 */
class AccountService extends ServiceBase
{
    /**
     * @var Account
     */
    protected Account $account;

    /**
     * LeadService constructor.
     * @param Lead $lead
     */
    public function __construct(Account $account)
    {

        parent::__construct($account);
        $this->account = $account;
    }

    /**
     * @return $this
     */
    public function convertAccount(): Account
    {
        $account = (new ConvertAccount($this->account))->execute();

        return $account;
    }

}
