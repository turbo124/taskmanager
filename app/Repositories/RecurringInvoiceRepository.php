<?php

namespace App\Repositories;

use App\NumberGenerator;
use App\Repositories\Base\BaseRepository;
use App\RecurringInvoice;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

/**
 * RecurringInvoiceRepository
 */
class RecurringInvoiceRepository extends BaseRepository
{
    /**
     * RecurringInvoiceRepository constructor.
     * @param RecurringInvoice $invoice
     */
    public function __construct(RecurringInvoice $invoice)
    {
        parent::__construct($invoice);
        $this->model = $invoice;
    }

    public function save($data, RecurringInvoice $invoice): ?RecurringInvoice
    {
        $invoice->fill($data);
        $invoice = $invoice->service()->calculateInvoiceTotals();
        $invoice->save();

        if (!$invoice->number) {
            $invoice->number = (new NumberGenerator)->getNextNumberForEntity($invoice->customer, $invoice);
        }

        return $invoice;
    }

    /**
     * List all the invoices
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function listInvoices(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Find the product by ID
     *
     * @param int $id
     *
     * @return Product
     * @throws ProductNotFoundException
     */
    public function findInvoiceById(int $id): RecurringInvoice
    {
        return $this->findOneOrFail($id);
    }

    public function getModel()
    {
        return $this->model;
    }
}
