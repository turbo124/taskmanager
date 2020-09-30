<?php

namespace App\Jobs\Expense;

use App\Factory\CloneExpenseToInvoiceFactory;
use App\Factory\CloneTaskToInvoiceFactory;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Task;
use App\Repositories\ExpenseRepository;
use App\Repositories\InvoiceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $expenses;

    private InvoiceRepository $invoice_repo;

    public function __construct(InvoiceRepository $invoice_repo, $expenses)
    {
        $this->expenses = $expenses;
        $this->invoice_repo = $invoice_repo;
    }

    public function handle()
    {
        $line_items = [];
        $customer = false;

        foreach ($this->expenses as $expense) {
            if ($expense === Expense::STATUS_INVOICED) {
                continue;
            }

            $notes = $expense->description . '\n';

            if (!empty($customer) && $expense->customer_id !== $customer) {
                continue;
            }

            $line_items[] = [
                'product_id'    => $expense->id,
                'unit_price'    => round($expense->amount * $expense->exchange_rate, 3),
                'quantity'      => 1,
                'type_id'       => Invoice::EXPENSE_TYPE,
                'description'   => !empty($expense->category) ? $expense->category->name : '',
                'unit_discount' => 0
            ];

            $expense->setStatus(Expense::STATUS_INVOICED);
            $expense->save();

            $customer = $expense->customer_id;
        }

        $first_expense = $this->expenses->first();
        $invoice = CloneExpenseToInvoiceFactory::create($first_expense, $first_expense->user, $first_expense->account);
        $this->invoice_repo->createInvoice(['line_items' => $line_items], $invoice);
    }
}