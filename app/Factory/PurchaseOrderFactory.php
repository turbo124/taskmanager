<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\Company;
use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderFactory
{
    /**
     * @param int $company_id
     * @param int $account_id
     * @param int $user_id
     * @param $total
     * @return PurchaseOrder
     */
    public static function create(
        Account $account,
        User $user,
        Company $company
    ): PurchaseOrder {
        $po = new PurchaseOrder();
        $po->setAccount($account);
        $po->setStatus(PurchaseOrder::STATUS_DRAFT);
        $po->setUser($user);
        $po->setCompany($company);

        return $quote;
    }
}
