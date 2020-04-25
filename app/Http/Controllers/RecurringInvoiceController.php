<?php

namespace App\Http\Controllers;

use App\ClientContact;
use App\Factory\CloneRecurringInvoiceFactory;
use App\Factory\CloneRecurringInvoiceToQuoteFactory;
use App\Factory\RecurringInvoiceFactory;
use App\Filters\InvoiceFilter;
use App\Notifications\ClientContactRequestCancellation;
use App\RecurringInvoice;
use App\Repositories\RecurringInvoiceRepository;
use App\Requests\RecurringInvoice\StoreRecurringInvoiceRequest;
use App\Requests\SearchRequest;
use App\Transformations\RecurringInvoiceTransformable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Filters\RecurringInvoiceFilter;
use App\Repositories\InvoiceRepository;
use App\Invoice;

/**
 * Class RecurringInvoiceController
 * @package App\Http\Controllers\RecurringInvoiceController
 */
class RecurringInvoiceController extends Controller
{
    use RecurringInvoiceTransformable;

    /**
     * @var RecurringInvoiceRepository
     */
    protected $recurring_invoice_repo;

    /**
     * RecurringInvoiceController constructor.
     * @param RecurringInvoiceRepository $recurring_invoice_repo
     */
    public function __construct(RecurringInvoiceRepository $recurring_invoice_repo)
    {
        $this->recurring_invoice_repo = $recurring_invoice_repo;
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        $invoices = (new RecurringInvoiceFilter($this->recurring_invoice_repo))->filter($request,
            auth()->user()->account_user()->account_id);
        return response()->json($invoices);
    }

    /**
     * @param StoreRecurringInvoiceRequest $request
     * @return mixed
     * @throws Exception
     */
    public function store(StoreRecurringInvoiceRequest $request)
    {
        $invoice = (new InvoiceRepository(new Invoice()))->findInvoiceById($request->invoice_id);

        $arrRecurring = array_merge(array(
            'sub_total'      => $invoice->sub_total,
            'tax_total'      => $invoice->tax_total,
            'discount_total' => $invoice->discount_total,
            'date'           => $invoice->date,
            'due_date'       => $invoice->due_date,
            'line_items'     => $invoice->line_items,
            'footer'         => $invoice->footer,
            'notes'          => $invoice->notes,
            'terms'          => $invoice->terms,
            'total'          => $invoice->total,
            'partial'        => $invoice->partial
        ), $request->all());

        $recurring_invoice = (new RecurringInvoiceRepository(new RecurringInvoice))->save($arrRecurring,
            RecurringInvoiceFactory::create($request->customer_id, auth()->user()->account_user()->account_id,
                $invoice->total));
        return response()->json($this->transformInvoice($recurring_invoice));
    }

    /**
     * @param int $id
     * @param StoreRecurringInvoiceRequest $request
     * @return mixed
     */
    public function update(int $id, StoreRecurringInvoiceRequest $request)
    {
        $recurring_invoice = $this->recurring_invoice_repo->findInvoiceById($id);

        $recurring_invoice = $this->recurring_invoice_repo->save($request->all(), $recurring_invoice);
        return response()->json($this->transformInvoice($recurring_invoice));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $invoice = $this->recurring_invoice_repo->findInvoiceById($id);
        $this->recurring_invoice_repo->archive($invoice);
        return response()->json([], 200);
    }

    public function destroy(int $id)
    {
        $recurring_invoice = RecurringInvoice::withTrashed()->where('id', '=', $id)->first();
        $this->recurring_invoice_repo->newDelete($recurring_invoice);
        return response()->json([], 200);
    }

    /**
     * @return mixed
     */
    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');
        $recurring_invoices = RecurringInvoice::withTrashed()->find($ids);

        return response()->json($recurring_invoices);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = RecurringInvoice::withTrashed()->where('id', '=', $id)->first();
        $this->recurring_invoice_repo->restore($group);
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestCancellation(Request $request)
    {
        $recurring_invoice = $this->recurring_invoice_repo->findInvoiceById($request->invoice_id);
        $client_contact = ClientContact::find($request->client_contact_id);
        $recurring_invoice->user->notify(new ClientContactRequestCancellation($recurring_invoice, $client_contact));
        return response()->json(['code' => 200]);
    }
}
