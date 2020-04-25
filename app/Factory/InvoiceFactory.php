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
        $invoice->balance = 0;
        $invoice->status_id = Invoice::STATUS_DRAFT;
        $invoice->discount_total = 0;
        $invoice->tax_total = 0;
        $invoice->footer = '';
        $invoice->terms = '';
        $invoice->public_notes = '';
        $invoice->private_notes = '';
        $invoice->tax_rate_name = '';
        $invoice->tax_rate = 0;
        $invoice->date = null;
        $invoice->partial_due_date = null;
        $invoice->total = 0;
        $invoice->user_id = $user_id;
        $invoice->partial = 0;
        $invoice->customer_id = $customer->id;
        $invoice->custom_value1 = '';
        $invoice->custom_value2 = '';
        $invoice->custom_value3 = '';
        $invoice->custom_value4 = '';

        return $invoice;
    }
}
