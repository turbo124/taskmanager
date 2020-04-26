<?php

namespace App\Factory;

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
    public static function create(int $account_id,
        int $user_id,
        Customer $customer): Invoice
    {
        $invoice = new Invoice();
        $invoice->account_id = $account_id;
        $invoice->status_id = Invoice::STATUS_DRAFT;
        $invoice->user_id = $user_id;
        $invoice->customer_id = $customer->id;

        return $invoice;
    }
}
