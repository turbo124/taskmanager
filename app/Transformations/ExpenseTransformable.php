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
            'expense_currency_id'   => (int)$expense->expense_currency_id ?: '',
            'invoice_category_id'   => (int)$expense->invoice_category_id ?: '',
            'payment_type_id'       => (int)$expense->payment_type_id ?: '',
            'recurring_expense_id'  => (int)$expense->recurring_expense_id ?: '',
            'is_deleted'            => (bool)$expense->is_deleted,
            'should_be_invoiced'    => (bool)$expense->should_be_invoiced,
            'invoice_documents'     => (bool)$expense->invoice_documents,
            'amount'                => (float)$expense->amount ?: 0,
            'foreign_amount'        => (float)$expense->foreign_amount ?: 0,
            'exchange_rate'         => (float)$expense->exchange_rate ?: 0,
            'tax_name1'             => $expense->tax_name1 ? $expense->tax_name1 : '',
            'tax_rate1'             => (float)$expense->tax_rate1,
            'tax_rate1'             => $expense->tax_name2 ? $expense->tax_name2 : '',
            'tax_rate2'             => (float)$expense->tax_rate2,
            'tax_name3'             => $expense->tax_name3 ? $expense->tax_name3 : '',
            'tax_rate3'             => (float)$expense->tax_rate3,
            'private_notes'         => (string)$expense->private_notes ?: '',
            'public_notes'          => (string)$expense->public_notes ?: '',
            'transaction_reference' => (string)$expense->transaction_reference ?: '',
            'transaction_id'        => (string)$expense->transaction_id ?: '',
            'expense_date'          => $expense->expense_date ?: '',
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
