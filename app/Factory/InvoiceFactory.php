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
    public static function create(Account $account,
        User $user,
        Customer $customer): Invoice
    {
        $invoice = new Invoice();
        $invoice->account_id = $account->id;
        $invoice->status_id = Invoice::STATUS_DRAFT;
        $invoice->user_id = $user->id;
        $invoice->customer_id = $customer->id;

        return $invoice;
    }
}
