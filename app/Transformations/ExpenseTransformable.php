<?php

namespace App\Transformations;

use App\Company;
use App\Expense;

trait ExpenseTransformable
{

    /**
     * @param Expense $expense
     * @return array
     */
    protected function transformExpense(Expense $expense)
    {
        return [
            'id'                    => $expense->id,
            'number'                => $expense->number ?: '',
            'user_id'               => $expense->user_id,
            'assigned_user_id'      => $expense->assigned_user_id,
            'company_id'            => $expense->company_id,
            'invoice_id'            => $expense->invoice_id,
            'customer_id'           => $expense->customer_id,
            'bank_id'               => (string)$expense->bank_id ?: '',
            'invoice_currency_id'   => (int)$expense->invoice_currency_id ?: '',
            'currency_id'           => (int)$expense->currency_id ?: '',
            'category_id'           => (int)$expense->category_id ?: '',
            'payment_type_id'       => (int)$expense->payment_type_id ?: '',
            'recurring_expense_id'  => (int)$expense->recurring_expense_id ?: '',
            'is_deleted'            => (bool)$expense->is_deleted,
            'create_invoice'        => (bool)$expense->create_invoice,
            'include_documents'     => (bool)$expense->include_documents,
            'amount'                => (float)$expense->amount ?: 0,
            'converted_amount'      => (float)$expense->converted_amount ?: 0,
            'exchange_rate'         => (float)$expense->exchange_rate ?: 0,
            'tax_rate_name'         => $expense->tax_rate_name ? $expense->tax_rate_name : '',
            'tax_rate'              => (float)$expense->tax_rate,
            'private_notes'         => (string)$expense->private_notes ?: '',
            'public_notes'          => (string)$expense->public_notes ?: '',
            'transaction_reference' => (string)$expense->transaction_reference ?: '',
            'transaction_id'        => (string)$expense->transaction_id ?: '',
            'date'                  => $expense->date ?: '',
            'payment_date'          => $expense->payment_date ?: '',
            'custom_value1'         => $expense->custom_value1 ?: '',
            'custom_value2'         => $expense->custom_value2 ?: '',
            'custom_value3'         => $expense->custom_value3 ?: '',
            'custom_value4'         => $expense->custom_value4 ?: '',
            'deleted_at'            => $expense->deleted_at,
            'updated_at'            => $expense->updated_at,
            'archived_at'           => $expense->deleted_at,
            'created_at'            => $expense->created_at,

        ];
    }

}
