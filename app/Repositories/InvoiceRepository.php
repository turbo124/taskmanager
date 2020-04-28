<?php

namespace App\Repositories;

use App\Customer;
use App\NumberGenerator;
use App\Factory\InvoiceInvitationFactory;
use App\Invoice;
use App\ClientContact;
use App\InvoiceInvitation;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use App\Repositories\PaymentRepository;
use App\Payment;
use App\Factory\InvoiceToPaymentFactory;
use Exception;
use Illuminate\Support\Collection;
use App\Task;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{

    /**
     * InvoiceRepository constructor.
     * @param Order $invoice
     */
    public function __construct(Invoice $invoice)
    {
        parent::__construct($invoice);
        $this->model = $invoice;
    }

    /**
     * @param int $id
     *
     * @return Invoice
     * @throws Exception
     */
    public function findInvoiceById(int $id): Invoice
    {
        return $this->findOneOrFail($id);
    }

    /**
     * List all the invoices
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listInvoices(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Task $objTask
     * @return Collection
     */
    public function getInvoiceForTask(Task $objTask): Invoice
    {

        return $this->model->where('task_id', '=', $objTask->id)->first();
    }

    public function findInvoicesByStatus(int $status): Collection
    {
        return $this->model->where('status_id', '=', $status)->get();
    }

    /**
     * @param array $data
     * @param Invoice $invoice
     * @return Invoice|null
     */
    public function save(array $data, Invoice $invoice): ?Invoice
    {
        $original_amount = $invoice->total;
        $invoice->fill($data);
        $invoice = $this->populateDefaults($invoice);
        $invoice = $invoice->service()->calculateInvoiceTotals();
        $invoice->setNumber();
      
        $invoice->save();

        $this->saveInvitations($invoice, 'invoice', $data);

        if($invoice->status_id !== Invoice::STATUS_DRAFT && $original_amount !== $invoice->total)
        {
            $invoice->ledger()->updateBalance(($invoice->total - $original_amount));
        }

        return $invoice->fresh();
    }

}
