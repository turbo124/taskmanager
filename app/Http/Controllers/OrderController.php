<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Events\Misc\InvitationWasViewed;
use App\Events\Order\OrderWasCreated;
use App\Factory\CloneOrderToInvoiceFactory;
use App\Factory\CloneOrderToQuoteFactory;
use App\Factory\OrderFactory;
use App\Invoice;
use App\Order;
use App\Quote;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\QuoteRepository;
use App\Requests\Order\CreateOrderRequest;
use Illuminate\Http\Request;
use App\Transformations\OrderTransformable;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    use OrderTransformable;

    private $order_repo;

    private $invoice_repo;

    /**
     * OrderController constructor.
     * @param OrderRepository $order_repo
     * @param InvoiceRepository $invoice_repo
     */
    public function __construct(OrderRepository $order_repo, InvoiceRepository $invoice_repo)
    {
        $this->order_repo = $order_repo;
        $this->invoice_repo = $invoice_repo;
    }

    public function update(int $id, Request $request)
    {
        $order = $this->order_repo->findOrderById($id);
        $this->order_repo->save($request->all(), $order);
        return response()->json($this->transformOrder($order));
    }

    /**
     * @param CreateOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrderRequest $request)
    {
        $customer = Customer::find($request->input('customer_id'));
        $order = $this->order_repo->save($request->all(),
            OrderFactory::create(auth()->user()->account_user()->account_id, auth()->user()->id, $customer));

        event(new OrderWasCreated($order));

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

    private function performAction(Request $request, Order $order, $action, $bulk = false)
    {
        switch ($action) {
            case 'clone_to_invoice':
                $invoice = CloneOrderToInvoiceFactory::create($order, auth()->user()->id,
                    auth()->user()->account_user()->account_id);
                (new InvoiceRepository(new Invoice))->save($request->all(), $invoice);
                return response()->json($invoice);
                break;
            case 'clone_to_quote':
                $quote = CloneOrderToQuoteFactory::create($order, auth()->user()->id,
                    auth()->user()->account_user()->account_id);
                (new QuoteRepository(new Quote))->save($request->all(), $quote);
                return response()->json($quote);
                break;
           
            case 'approve':
                if ($order->status_id != Order::STATUS_SENT) {
                    return response()->json(['message' => 'Unable to approve this order as it has expired.'], 400);
                }

                return response()->json($order->service()->convert($this->invoice_repo, $this->order_repo)->save());
                break;
            case 'download':
                $disk = config('filesystems.default');
                $content = Storage::disk($disk)->get($order->service()->getPdf(null));
                return response()->json(['data' => base64_encode($content)]);
                break;
            case 'archive':
                $this->order_repo->archive($order);
                if (!$bulk) {
                    return response()->json($order);
                }
                break;
            case 'delete':
                $this->order_repo->newDelete($order);
                if (!$bulk) {
                    return response()->json($order);
                }
                break;
            case 'email':
                $order->service()->sendEmail(null);
                if (!$bulk) {
                    return response()->json(['message' => 'email sent'], 200);
                }
                break;
            default:
                return response()->json(['message' => "The requested action `{$action}` is not available."], 400);
                break;
        }
    }

    public function bulk(Request $request)
    {
        $action = $request->action;

        $ids = $request->input('ids');

        $orders = Order::withTrashed()->whereIn('id', $ids)->get();

        if (!$orders) {
            return response()->json(['message' => 'No Orders Found']);
        }

        $orders->each(function ($order, $key) use ($action, $request) {
            $this->performAction($request, $order, $action, true);
        });

        return response()->json(Order::withTrashed()->whereIn('id', $ids));
    }

    public function downloadPdf()
    {
        $ids = request()->input('ids');

        $orders = Order::withTrashed()->whereIn('id', $ids)->get();

        if (!$orders) {
            return response()->json(['message' => 'No Orders Found']);
        }

        $disk = config('filesystems.default');
        $pdfs = [];

        foreach ($orders as $order) {
            $content = Storage::disk($disk)->get($order->service()->getPdf(null));
            $pdfs[$order->number] = base64_encode($content);
        }

        return response()->json(['data' => $pdfs]);
    }

    /**
     * @param $invitation_key
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function markViewed($invitation_key)
    {
        $invitation = $this->order_repo->getInvitationByKey($invitation_key);
        $contact = $invitation->contact;
        $order = $invitation->order;

        $disk = config('filesystems.default');
        $content = Storage::disk($disk)->get($order->service()->getPdf($contact));

        if (request()->has('markRead') && request()->input('markRead') === 'true') {
            $invitation->markViewed();
            event(new InvitationWasViewed('order', $invitation));
        }

        return response()->json(['data' => base64_encode($content)]);
    }
}
