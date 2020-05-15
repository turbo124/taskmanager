<?php

namespace App\Repositories;

use App\Account;
use App\Customer;
use App\Filters\InvoiceFilter;
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
use App\Requests\SearchRequest;
use Exception;
use Illuminate\Support\Collection;
use App\Jobs\Inventory\UpdateInventory;
use App\Task;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{

    /**
     * InvoiceRepository constructor.
     * @param Invoice $invoice
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
     * @param SearchRequest $search_request
     * @param Account $account
     * @return InvoiceFilter|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new InvoiceFilter($this))->filter($search_request, $account);
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

        if ($invoice->status_id !== Invoice::STATUS_DRAFT && $original_amount !== $invoice->total) {
            $invoice->ledger()->updateBalance(($invoice->total - $original_amount));
        }

        if (!empty($invoice->line_items) && $invoice->customer->getSetting('should_update_inventory')) {
            UpdateInventory::dispatch($invoice->line_items);
        }

        return $invoice->fresh();
    }

}
