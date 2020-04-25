<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Customer;
use App\Events\Invoice\InvoiceWasCreated;
use App\Events\Misc\InvitationWasViewed;
use App\Factory\CloneInvoiceFactory;
use App\Factory\InvoiceToPaymentFactory;
use App\InvoiceInvitation;
use App\Factory\CloneInvoiceToQuoteFactory;
use App\Factory\NotificationFactory;
use App\Jobs\Order\InvoiceOrders;
use App\Jobs\Pdf\Download;
use App\Jobs\RecurringInvoice\SaveRecurringInvoice;
use App\Notification;
use App\Payment;
use App\Quote;
use App\Repositories\CreditRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\QuoteRepository;
use App\Transformations\QuoteTransformable;
use App\Utils\Number;
use Exception;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Transformations\InvoiceTransformable;
use App\Invoice;
use App\Requests\SearchRequest;
use App\Requests\Invoice\CreateInvoiceRequest;
use App\Requests\Invoice\UpdateInvoiceRequest;
use App\Factory\InvoiceFactory;
use App\Events\Invoice\InvoiceWasUpdated;
use App\Filters\InvoiceFilter;
use App\Repositories\TaskRepository;
use App\Task;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    use InvoiceTransformable, QuoteTransformable;

    private $invoice_repo;

    /**
     * InvoiceController constructor.
     * @param InvoiceRepositoryInterface $invoice_repo
     */
    public function __construct(InvoiceRepositoryInterface $invoice_repo)
    {
        $this->invoice_repo = $invoice_repo;
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $invoices =
            (new InvoiceFilter($this->invoice_repo))->filter($request, auth()->user()->account_user()->account_id);
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
        $invoice = $this->invoice_repo->save($request->all(),
            InvoiceFactory::create(auth()->user()->account_user()->account_id, auth()->user()->id, $customer));
        InvoiceOrders::dispatchNow($invoice);
        event(new InvoiceWasCreated($invoice));
        SaveRecurringInvoice::dispatchNow($request, $invoice->account, $invoice);

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

        $invoice = $this->invoice_repo->save($request->all(), $invoice);
        //SaveRecurringInvoice::dispatchNow($request, $invoice->account);
        InvoiceOrders::dispatchNow($invoice);
        event(new InvoiceWasUpdated($invoice, $invoice->account));
        $invoiceTransformed = $this->transformInvoice($invoice);
        return $invoiceTransformed->toJson();
    }

    public function action(Request $request, Invoice $invoice, $action)
    {
        return $this->performAction($request, $invoice, $action);
    }

    private function performAction(Request $request, Invoice $invoice, $action, $bulk = false)
    {
        switch ($action) {
            case 'clone_to_invoice':
                $invoice = CloneInvoiceFactory::create($invoice, auth()->user()->id,
                    auth()->user()->account_user()->account_id);
                $this->invoice_repo->save($request->all(), $invoice);
                return response()->json($this->transformInvoice($invoice));
                break;
            case 'clone_to_quote':
                $quote = CloneInvoiceToQuoteFactory::create($invoice, auth()->user()->id);
                (new QuoteRepository(new Quote))->save($request->all(), $quote);
                return response()->json($this->transformQuote($quote));
                break;
            case 'mark_paid':
                 $invoice = $invoice->service()->markPaid($this->invoice_repo, new PaymentRepository(new Payment));

                if (!$invoice) {
                    return response()->json('Unable to mark invoice as paid', 400);
                }
               
                if (!$bulk) {
                    return response()->json($this->transformInvoice($invoice));
                }
                break;
            case 'mark_sent':
                $invoice = $this->invoice_repo->markSent($invoice);
                $invoice->customer->service()->updateBalance($invoice->balance)->save();
                $invoice->ledger()->updateBalance($invoice->balance);
                 
                if (!$bulk) {
                    return response()->json($this->transformInvoice($invoice));
                }
                break;
            case 'download':
                $disk = config('filesystems.default');
                $content = Storage::disk($disk)->get($invoice->service()->getPdf(null));
                return response()->json(['data' => base64_encode($content)]);
                break;
            case 'archive':
                $this->invoice_repo->archive($invoice);
                if (!$bulk) {
                    return response()->json($this->transformInvoice($invoice));
                }
                break;
            case 'delete':
                $this->invoice_repo->newDelete($invoice);
                if (!$bulk) {
                    return response()->json($this->transformInvoice($invoice));
                }
                break;
            case 'reverse':
                $invoice = $invoice->service()->handleReversal(new CreditRepository(new Credit), new PaymentRepository(new Payment))->save();

                if (!$bulk) {
                    return response()->json($this->transformInvoice($invoice));
                }
                break;

            case 'cancel':
                $invoice = $invoice->service()->handleCancellation()->save();

                if (!$bulk) {
                    return response()->json($this->transformInvoice($invoice));
                }
                break;

            case 'email':
                $invoice->service()->sendEmail(null);
                if (!$bulk) {
                    return response()->json(['message' => 'email sent'], 200);
                }
                break;
            default:
                return response()->json(['message' => "The requested action `{$action}` is not available."], 400);
                break;
        }
    }

    public function downloadPdf(Request $request)
    {

        $ids = request()->input('ids');

        $invoices = Invoice::withTrashed()->whereIn('id', $ids)->get();

        if (!$invoices) {
            return response()->json(['message' => 'No Invoices Found']);
        }

        $disk = config('filesystems.default');
        $pdfs = [];

        foreach ($invoices as $invoice) {
            $content = Storage::disk($disk)->get($invoice->service()->getPdf(null));
            $pdfs[$invoice->number] = base64_encode($content);
        }

        return response()->json(['data' => $pdfs]);
    }

    public function markViewed($invitation_key)
    {
        $invitation = $this->invoice_repo->getInvitation(['key' => $invitation_key], 'invoice');
        $contact = $invitation->contact;
        $invoice = $invitation->invoice;

        $disk = config('filesystems.default');
        $content = Storage::disk($disk)->get($invoice->service()->getPdf($contact));

        if (request()->has('markRead') && request()->input('markRead') === 'true') {
            $invitation->markViewed();
            event(new InvitationWasViewed('invoice', $invitation));
        }

        return response()->json(['data' => base64_encode($content)]);
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

    public function destroy(int $id)
    {
        $invoice = $this->invoice_repo->findInvoiceById($id);
        $invoice->deleteInvoice($invoice);
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

    public function bulk(Request $request)
    {
        /*
         * WIP!
         */
        $action = request()->input('action');

        $ids = request()->input('ids');

        $invoices = Invoice::withTrashed()->whereIn('id', $ids)->get();

        if (!$invoices) {
            return response()->json(['message' => 'No Invoices Found']);
        }


        if ($action == 'download' && $invoices->count() > 1) {

            Download::dispatch($invoices, $invoices->first()->account, auth()->user()->email);

            return response()->json(['message' => 'Email Sent!'], 200);
        }


        $invoices->each(function ($invoice, $key) use ($action, $request) {
            $this->performAction($request, $invoice, $action, true);
        });

        return $this->response->json($invoices);
    }
}
