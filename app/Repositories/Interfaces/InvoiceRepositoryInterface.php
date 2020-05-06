<?php

namespace App\Repositories\Interfaces;

use App\Account;
use App\Invoice;
use App\Requests\SearchRequest;
use App\Task;
use Illuminate\Support\Collection;

interface InvoiceRepositoryInterface
{
    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     */
    public function getAll(SearchRequest $search_request, Account $account);

    /**
     * @param int $id
     * @return Invoice
     */
    public function findInvoiceById(int $id): Invoice;

    /**
     *
     * @param \App\Repositories\Interfaces\Task $objTask
     */
    public function getInvoiceForTask(Task $objTask): Invoice;

    /**
     * @param int $status
     * @return Collection
     */
    public function findInvoicesByStatus(int $status): Collection;

    /**
     * @param array $data
     * @param Invoice $invoice
     * @return Invoice|null
     */
    public function save(array $data, Invoice $invoice): ?Invoice;

}
