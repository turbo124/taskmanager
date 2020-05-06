<?php

namespace App\Services\Ledger;

use App\CompanyLedger;
use App\Factory\CompanyLedgerFactory;
use App\Services\ServiceBase;

class LedgerService extends ServiceBase
{

    private $entity;

    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    public function updateBalance($adjustment, $notes = '')
    {
        $company_ledger = $this->ledger();

        $balance = 0;

        if ($company_ledger) {
            $balance = $company_ledger->balance;
        }

        $company_ledger = new CompanyLedger;
        $company_ledger->setAccount($this->entity->account);
        $company_ledger->setUser($this->entity->user);
        $company_ledger->setCustomer($this->entity->customer);
        $company_ledger->setBalance($balance + $adjustment);
        $company_ledger->setAdjustment($adjustment);
        $company_ledger->setNotes($notes);
        $company_ledger->createLedger();

        $this->entity->company_ledger()->save($company_ledger);

        return $company_ledger;
    }

    private function ledger(): ?CompanyLedger
    {
        return CompanyLedger::whereCustomerId($this->entity->customer_id)
                            ->whereAccountId($this->entity->account_id)
                            ->orderBy('id', 'DESC')
                            ->first();
    }
}
