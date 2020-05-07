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
use App\Repositories\CreditRepository;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Notification;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\QuoteRepository;
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
class QuoteController extends BaseController
{

    use QuoteTransformable, InvoiceTransformable;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoice_repo;

    /**
     * @var QuoteRepositoryInterface
     */
    private QuoteRepositoryInterface $quote_repo;

    /**
     * QuoteController constructor.
     * @param InvoiceRepositoryInterface $invoice_repo
     * @param QuoteRepositoryInterface $quote_repo
     */
    public function __construct(InvoiceRepositoryInterface $invoice_repo, QuoteRepositoryInterface $quote_repo, CreditRepository $credit_repo)
    {
        $this->invoice_repo = $invoice_repo;
        $this->quote_repo = $quote_repo;
        parent::__construct($invoice_repo, $quote_repo, $credit_repo, 'Quote');
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
}
