<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Customer;
use App\Events\Invoice\InvoiceWasCreated;
use App\Events\Invoice\InvoiceWasRestored;
use App\Events\Misc\InvitationWasViewed;
use App\Factory\CloneInvoiceFactory;
use App\Factory\InvoiceToPaymentFactory;
use App\Helpers\Refund\RefundFactory;
use App\Models\InvoiceInvitation;
use App\Factory\CloneInvoiceToQuoteFactory;
use App\Factory\NotificationFactory;
use App\Jobs\Order\InvoiceOrders;
use App\Jobs\Pdf\Download;
use App\Jobs\RecurringInvoice\SaveRecurringInvoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Quote;
use App\Repositories\CreditRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\QuoteRepository;
use App\Transformations\QuoteTransformable;
use Exception;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Transformations\InvoiceTransformable;
use App\Models\Invoice;
use App\Requests\SearchRequest;
use App\Requests\Invoice\CreateInvoiceRequest;
use App\Requests\Invoice\UpdateInvoiceRequest;
use App\Factory\InvoiceFactory;
use App\Events\Invoice\InvoiceWasUpdated;
use App\Filters\InvoiceFilter;
use App\Repositories\TaskRepository;
use App\Models\Task;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

/**
 * Class InvoiceController
 * @package App\Http\Controllers
 */
class InvoiceController extends BaseController
{

    use InvoiceTransformable, QuoteTransformable;

    /**
     * @var InvoiceRepositoryInterface|InvoiceRepository
     */
    private InvoiceRepository $invoice_repo;

    /**
     * InvoiceController constructor.
     * @param InvoiceRepositoryInterface $invoice_repo
     * @param QuoteRepository $quote_repo
     * @param CreditRepository $credit_repo
     */
    public function __construct(
        InvoiceRepositoryInterface $invoice_repo,
        QuoteRepository $quote_repo,
        CreditRepository $credit_repo
    ) {
        $this->invoice_repo = $invoice_repo;
        parent::__construct($invoice_repo, $quote_repo, $credit_repo, 'Invoice');
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $invoices =
            (new InvoiceFilter($this->invoice_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($invoices);
    }

    public function getInvoicesByStatus(int $status)
    {
        $invoices = $this->invoice_repo->findInvoicesByStatus($status);
        return response()->json($invoices);
    }

    /**
     * @param CreateInvoiceRequest $request
     * @return string
     */
    public function store(CreateInvoiceRequest $request)
    {
        $customer = Customer::find($request->input('customer_id'));
        $invoice = $this->invoice_repo->createInvoice(
            $request->all(),
            InvoiceFactory::create(auth()->user()->account_user()->account, auth()->user(), $customer)
        );

        return response()->json($this->transformInvoice($invoice));
    }

    /**
     * @param int $invoice_id
     * @return mixed
     */
    public function show(int $invoice_id)
    {
        $invoice = $this->invoice_repo->findInvoiceById($invoice_id);
        return response()->json($this->transformInvoice($invoice));
    }

    /**
     * @param int $task_id
     * @return mixed
     * @throws Exception
     */
    public function getInvoiceLinesForTask(int $task_id)
    {
        $task = (new TaskRepository(new Task))->findTaskById($task_id);
        $invoice = $this->invoice_repo->getInvoiceForTask($task);

        if (!$invoice->count()) {
            return response()->json('empty');
        }

        $arrTest = [
            'lines'   => $invoice->line_items,
            'invoice' => $invoice
        ];

        return response()->json($arrTest);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function update(UpdateInvoiceRequest $request, int $id)
    {
        $invoice = $this->invoice_repo->findInvoiceById($id);

        if ($invoice->isLocked()) {
            return response()->json(['message' => trans('texts.invoice_is_locked')], 422);
        }

        $invoice = $this->invoice_repo->updateInvoice($request->all(), $invoice);
        return response()->json($this->transformInvoice($invoice));
    }

    public function action(Request $request, Invoice $invoice, $action)
    {
        return $this->performAction($request, $invoice, $action);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $invoice = $this->invoice_repo->findInvoiceById($id);
        $this->invoice_repo->archive($invoice);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy(int $id)
    {
        $invoice = $this->invoice_repo->findInvoiceById($id);
        $invoice->service()->cancelInvoice();
        $invoice->deleteInvoice();
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $invoice = Invoice::withTrashed()->where('id', '=', $id)->first();
        $this->invoice_repo->restore($invoice);
        return response()->json([], 200);
    }
}
