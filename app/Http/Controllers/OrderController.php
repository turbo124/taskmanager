<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Events\Misc\InvitationWasViewed;
use App\Events\Order\OrderWasCreated;
use App\Events\Order\OrderWasUpdated;
use App\Factory\CloneOrderToInvoiceFactory;
use App\Factory\CloneOrderToQuoteFactory;
use App\Factory\OrderFactory;
use App\Filters\InvoiceFilter;
use App\Filters\OrderFilter;
use App\Invoice;
use App\Order;
use App\Quote;
use App\Repositories\CreditRepository;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\QuoteRepository;
use App\Requests\Order\CreateOrderRequest;
use App\Requests\SearchRequest;
use Illuminate\Http\Request;
use App\Transformations\OrderTransformable;
use Illuminate\Support\Facades\Storage;

class OrderController extends BaseController
{
    use OrderTransformable;

    /**
     * @var OrderRepository
     */
    private OrderRepository $order_repo;

    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoice_repo;

    /**
     * OrderController constructor.
     * @param OrderRepository $order_repo
     * @param InvoiceRepository $invoice_repo
     * @param QuoteRepository $quote_repo
     * @param CreditRepository $credit_repo
     */
    public function __construct(
        OrderRepository $order_repo,
        InvoiceRepository $invoice_repo,
        QuoteRepository $quote_repo,
        CreditRepository $credit_repo
    ) {
        parent::__construct($invoice_repo, $quote_repo, $credit_repo, 'Order');
        $this->order_repo = $order_repo;
        $this->invoice_repo = $invoice_repo;
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $invoices =
            (new OrderFilter($this->order_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($invoices);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(int $id, Request $request)
    {
        $order = $this->order_repo->findOrderById($id);
        $this->order_repo->updateOrder($request->all(), $order);
        return response()->json($this->transformOrder($order));
    }

    /**
     * @param CreateOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrderRequest $request)
    {
        $customer = Customer::find($request->input('customer_id'));
        $order = $this->order_repo->createOrder(
            $request->all(),
            OrderFactory::create(auth()->user()->account_user()->account, auth()->user(), $customer)
        );

        return response()->json($this->transformOrder($order));
    }

    public function getOrderForTask(int $task_id)
    {
        $order = Order::whereTaskId($task_id)->first();
        return response()->json($this->transformOrder($order));
    }

    public function action(Request $request, Order $order, $action)
    {
        return $this->performAction($request, $order, $action);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $order = $this->order_repo->findOrderById($id);
        $this->order_repo->archive($order);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $order = $this->order_repo->findOrderById($id);
        $this->order_repo->newDelete($order);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $order = $this->order_repo->findOrderById($id);
        $this->order_repo->restore($order);
        return response()->json([], 200);
    }
}
