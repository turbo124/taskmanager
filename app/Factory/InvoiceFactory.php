<?php

namespace App\Factory;

use App\Account;
use App\User;
use App\Customer;
use App\Invoice;
use Illuminate\Support\Facades\Log;

class InvoiceFactory
{
    /**
     * @param int $customer_id
     * @param $user_id
     * @param $account_id
     * @param int $total
     * @param object|null $settings
     * @param Customer|null $customer
     * @return Invoice
     */
    public static function create(
        Account $account,
        User $user,
        Customer $customer
    ): Invoice {
        $invoice = new Invoice();
        $invoice->setAccount($account);
        $invoice->setStatus(Invoice::STATUS_DRAFT);
        $invoice->setUser($user);
        $invoice->setCustomer($customer);

        return $invoice;
    }
}
