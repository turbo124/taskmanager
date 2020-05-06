<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Events\Misc\InvitationWasViewed;
use App\Factory\CloneQuoteFactory;
use App\Factory\CloneQuoteToOrderFactory;
use App\Factory\NotificationFactory;
use App\Jobs\Order\QuoteOrders;
use App\Jobs\Pdf\Download;
use App\Jobs\RecurringQuote\SaveRecurringQuote;
use App\Order;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Notification;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TaskRepository;
use App\Requests\Quote\CreateQuoteRequest;
use App\Requests\Quote\UpdateQuoteRequest;
use App\Requests\SearchRequest;
use App\Task;
use App\Transformations\InvoiceTransformable;
use App\Factory\QuoteFactory;
use App\Transformations\QuoteTransformable;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Quote;
use App\Repositories\Interfaces\QuoteRepositoryInterface;
use Illuminate\Http\Request;
use App\Filters\QuoteFilter;
use App\Factory\CloneQuoteToInvoiceFactory;
use Illuminate\Support\Facades\Storage;
use App\Events\Quote\QuoteWasCreated;
use App\Events\Quote\QuoteWasUpdated;

/**
 * Class QuoteController
 * @package App\Http\Controllers
 */
class QuoteController extends Controller
{

    use QuoteTransformable, InvoiceTransformable;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoice_repo;

    /**
     * @var QuoteRepositoryInterface
     */
    private $quote_repo;

    /**
     * QuoteController constructor.
     * @param InvoiceRepositoryInterface $invoice_repo
     * @param QuoteRepositoryInterface $quote_repo
     */
    public function __construct(InvoiceRepositoryInterface $invoice_repo, QuoteRepositoryInterface $quote_repo)
    {
        $this->invoice_repo = $invoice_repo;
        $this->quote_repo = $quote_repo;
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        $invoices = (new QuoteFilter($this->quote_repo))->filter($request, auth()->user()->account_user()->account_id);
        return response()->json($invoices);
    }

    /**
     * @param int $quote_id
     * @return mixed
     */
    public function show(int $quote_id)
    {
        $invoice = $this->quote_repo->findQuoteById($quote_id);
        return response()->json($invoice);
    }

    /**
     * @param CreateQuoteRequest $request
     * @return mixed
     */
    public function store(CreateQuoteRequest $request)
    {
        $customer = Customer::find($request->input('customer_id'));
        $quote = $this->quote_repo->save($request->all(),
            QuoteFactory::create(auth()->user()->account_user()->account, auth()->user(), $customer));
        SaveRecurringQuote::dispatchNow($request, $quote->account, $quote);
        QuoteOrders::dispatchNow($quote);
        event(new QuoteWasCreated($quote));
        return response()->json($this->transformQuote($quote));
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update(UpdateQuoteRequest $request, int $id)
    {
        $quote = $this->quote_repo->findQuoteById($id);

        $quote = $this->quote_repo->save($request->all(), $quote);
        //SaveRecurringQuote::dispatchNow($request, $quote->account);
        QuoteOrders::dispatchNow($quote);
        event(new QuoteWasUpdated($quote));
        return response()->json($this->transformQuote($quote));
    }

    /**
     * @param Request $request
     * @param Quote $quote
     * @param $action
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function action(Request $request, Quote $quote, $action)
    {
        return $this->performAction($request, $quote, $action);
    }

    /**
     * @param Request $request
     * @param Quote $quote
     * @param $action
     * @param bool $bulk
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function performAction(Request $request, Quote $quote, $action, $bulk = false)
    {
        switch ($action) {
            case 'clone_to_invoice':
                $invoice = $this->invoice_repo->save($request->all(),
                    CloneQuoteToInvoiceFactory::create($this->quote_repo->findQuoteById($quote->id), auth()->user()->id,
                        auth()->user()->account_user()->account_id));
                return response()->json($this->transformInvoice($invoice));
                break;
            case 'clone_to_order':
                $order = (new OrderRepository(new Order))->save($request->all(),
                    CloneQuoteToOrderFactory::create($this->quote_repo->findQuoteById($quote->id), auth()->user()->id,
                        auth()->user()->account_user()->account_id));
                return response()->json($order);
                break;
            case 'clone_to_quote':
                $quote = CloneQuoteFactory::create($quote, auth()->user());
                $this->quote_repo->save($request->all(), $quote);
                return response()->json($this->transformQuote($quote));
                break;
            case 'mark_sent':
                $quote = $this->quote_repo->markSent($quote);

                if (!$bulk) {
                    return response()->json($quote);
                }

                break;
            case 'approve':
                $quote = $quote->service()->approve($this->invoice_repo, $this->quote_repo);

                if (!$quote) {
                    return response()->json(['message' => 'Unable to approve this quote as it has expired.'], 400);
                }

                $quote->save();

                return response()->json($quote);
                break;
            case 'download':
                $disk = config('filesystems.default');
                $content = Storage::disk($disk)->get($quote->service()->getPdf(null));
                return response()->json(['data' => base64_encode($content)]);
                break;
            case 'archive':
                $this->invoice_repo->archive($quote);
                return response()->json($quote);
                break;
            case 'delete':
                $this->quote_repo->newDelete($quote);
                return response()->json($quote);
                break;
            case 'email':
                $subject = $quote->customer->getSetting('email_subject_quote');
                $body = $quote->customer->getSetting('email_template_quote');
                $quote->service()->sendEmail(null, $subject, $body);
                return response()->json(['message' => 'email sent'], 200);
                break;
            default:
                return response()->json(['message' => "The requested action `{$action}` is not available."], 400);
                break;
        }
    }

    /**
     * @param int $task_id
     * @return mixed
     * @throws Exception
     */
    public function getQuoteLinesForTask(int $task_id)
    {
        $task = (new TaskRepository(new Task))->findTaskById($task_id);
        $quote = $this->quote_repo->getQuoteForTask($task);

        if (!$quote->count()) {
            return response()->json('empty');
        }

        $arrTest = [
            'lines'   => $quote->line_items,
            'invoice' => $quote
        ];

        return response()->json($arrTest);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $invoice = $this->quote_repo->findQuoteById($id);
        $this->quote_repo->archive($invoice);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $quote = Quote::withTrashed()->where('id', '=', $id)->first();
        $this->quote_repo->newDelete($quote);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $invoice = Quote::withTrashed()->where('id', '=', $id)->first();
        $this->quote_repo->restore($invoice);
        return response()->json([], 200);
    }

    public function bulk(Request $request)
    {
        $action = $request->action;

        $ids = $request->ids;

        $quotes = Quote::withTrashed()->whereIn('id', $ids)->get();

        if (!$quotes) {
            return response()->json(['message' => 'No Quotes Found']);
        }

        /*
         * Download Invoice/s
         */

        if ($action == 'download' && $quotes->count() > 1) {

            Download::dispatch($quotes, $quotes->first()->account, auth()->user()->email);

            return response()->json(['message' => 'Email Sent!'], 200);
        }

        /*
           * Send the other actions to the switch
           */
        $quotes->each(function ($quote, $key) use ($action, $request) {
            $this->performAction($request, $quote, $action, true);
        });

        /* Need to understand which permission are required for the given bulk action ie. view / edit */
        return response()->json($quotes);
    }

    public function downloadPdf()
    {
        $ids = request()->input('ids');

        $quotes = Quote::withTrashed()->whereIn('id', $ids)->get();

        if (!$quotes) {
            return response()->json(['message' => 'No Quotes Found']);
        }

        $disk = config('filesystems.default');
        $pdfs = [];

        foreach ($quotes as $quote) {
            $content = Storage::disk($disk)->get($quote->service()->getPdf(null));
            $pdfs[$quote->number] = base64_encode($content);
        }

        return response()->json(['data' => $pdfs]);
    }

    public function markViewed($invitation_key)
    {
        $invitation = $this->quote_repo->getInvitationByKey($invitation_key);
        $contact = $invitation->contact;
        $quote = $invitation->quote;

        $disk = config('filesystems.default');
        $content = Storage::disk($disk)->get($quote->service()->getPdf($contact));

        if (request()->has('markRead') && request()->input('markRead') === 'true') {
            $invitation->markViewed();
            event(new InvitationWasViewed('quote', $invitation));
        }

        return response()->json(['data' => base64_encode($content)]);
    }
}
