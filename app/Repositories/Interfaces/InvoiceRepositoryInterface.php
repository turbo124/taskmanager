<?php

namespace App\Repositories\Interfaces;

use App\Invoice;
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
    public function listInvoices(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    /**
     *
     * @param int $id
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
