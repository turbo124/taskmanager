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
        $purchase_order = new PurchaseOrder();
        $purchase_order->setAccount($account);
        $purchase_order->setStatus(PurchaseOrder::STATUS_DRAFT);
        $purchase_order->setUser($user);
        $purchase_order->setCompany($company);

        return $purchase_order;
    }
}
