<?php

namespace App\Repositories;

use App\Events\Invoice\InvoiceWasCreated;
use App\Events\Invoice\InvoiceWasUpdated;
use App\Filters\InvoiceFilter;
use App\Jobs\Order\InvoiceOrders;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Task;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Requests\SearchRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
     * @return InvoiceFilter|LengthAwarePaginator
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
     * @param Quote $quote
     * @return Quote|null
     */
    public function updateInvoice(array $data, Invoice $invoice): ?Invoice
    {
        $invoice = $this->save($data, $invoice);
        InvoiceOrders::dispatchNow($invoice);
        event(new InvoiceWasUpdated($invoice));

        return $invoice;
    }

    /**
     * @param array $data
     * @param Invoice $invoice
     */
    public function createInvoice(array $data, Invoice $invoice): ?Invoice
    {
        $invoice = $this->save($data, $invoice);

        InvoiceOrders::dispatchNow($invoice);

        if (!empty($data['recurring'])) {
            $recurring = json_decode($data['recurring'], true);
            $invoice->service()->createRecurringInvoice($recurring);
        }

        event(new InvoiceWasCreated($invoice));

        return $invoice;
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
            $updated_amount = $invoice->total - $original_amount;
            $invoice->transaction_service()->createTransaction($updated_amount, $invoice->customer->balance);
        }


        return $invoice->fresh();
    }

    public function getInvoicesForAutoBilling()
    {
        return Invoice::where('is_deleted', 0)
                      ->whereNull('deleted_at')
                      ->whereNull('is_recurring')
                      ->whereNotNull('recurring_invoice_id')
                      ->where('balance', '>', 0)
                      ->where('due_date', Carbon::today())
                      ->get();
    }

}
