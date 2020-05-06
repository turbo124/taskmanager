<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Customer;
use App\Events\Payment\PaymentWasCreated;
use App\Factory\NotificationFactory;
use App\Invoice;
use App\Refund;
use App\Repositories\CreditRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Requests\Payment\CreatePaymentRequest;
use App\Requests\Payment\RefundPaymentRequest;
use App\Requests\Payment\UpdatePaymentRequest;
use App\Requests\SearchRequest;
use App\Transformations\PaymentTransformable;
use App\Payment;
use App\Filters\PaymentFilter;
use App\Factory\PaymentFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{

    use PaymentTransformable;

    /**
     * @var PaymentRepositoryInterface
     */
    private $payment_repo;

    /**
     * PaymentController constructor.
     * @param PaymentRepositoryInterface $payment_repo
     */
    public function __construct(PaymentRepositoryInterface $payment_repo)
    {
        $this->payment_repo = $payment_repo;
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        $payments =
            (new PaymentFilter($this->payment_repo))->filter($request, auth()->user()->account_user()->account_id);
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     * @param CreatePaymentRequest $request
     * @return mixed
     */
    public function store(CreatePaymentRequest $request)
    {
        $payment =
        $payment = $this->payment_repo->processPayment($request->all(),
            PaymentFactory::create(Customer::where('id', $request->customer_id)->first(), auth()->user(),
                auth()->user()->account_user()->account));

        $notification = NotificationFactory::create(auth()->user()->account_user()->account_id, auth()->user()->id);
        (new NotificationRepository(new \App\Notification))->save($notification, [
            'data' => json_encode(['id' => $payment->id, 'message' => 'A new payment was created']),
            'type' => 'App\Notifications\PaymentCreated'
        ]);

        return response()->json($this->transformPayment($payment));
    }

    public function show(int $id)
    {
        $payment = $this->payment_repo->findPaymentById($id);
        return response()->json($this->transformPayment($payment));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdatePaymentRequest $request
     * @param $id
     * @return mixed
     */
    public function update(UpdatePaymentRequest $request, $id)
    {
        $payment = $this->payment_repo->findPaymentById($id);

        $payment = (new PaymentRepository($payment))->processPayment($request->all(), $payment);
        return response()->json($this->transformPayment($payment));
    }

    public function archive(int $id)
    {
        $payment = Payment::withTrashed()->where('id', '=', $id)->first();
        $this->payment_repo->archive($payment);
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $payment = $this->payment_repo->findPaymentById($id);
        $payment->deletePayment();

        return response()->json('deleted');
    }

    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');
        $payments = Payment::withTrashed()->find($ids);
        $payments->each(function ($payment, $key) use ($action) {
            $this->payment_repo->{$action}($payment);
        });
        return response()->json(Payment::withTrashed()->whereIn('id', $ids));
    }

    /**
     * @param Request $request
     * @param Payment $payment
     * @param $action
     */
    public function action(Request $request, Payment $payment, $action)
    {
        switch ($action) {
            case 'refund':
                (new Refund($payment, new CreditRepository(new Credit), $request->all()))->refund();
                break;
            case 'email':
                $payment->service()->sendEmail();
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * @param RefundPaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refund(RefundPaymentRequest $request)
    {
        $payment = $request->payment();

        (new Refund($payment, new CreditRepository(new Credit), $request->all()))->refund();

        return response()->json($payment);
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = Payment::withTrashed()->where('id', '=', $id)->first();
        $this->payment_repo->restore($group);
        return response()->json([], 200);
    }

    private function attachInvoices(Customer $customer, Payment $payment, $ids): Payment
    {
        $invoices = Invoice::whereIn('id', explode(",", $ids))
                           ->whereCustomerId($customer->id)
                           ->get();

        foreach ($invoices as $invoice) {
            $payment->attachInvoice($invoice);
            $payment->ledger()->updateBalance($invoice->balance * -1);
            $payment->customer->service()->updateBalance($invoice->balance * -1)
                              ->updatePaidToDate($invoice->balance)->save();
            $invoice->resetPartialInvoice($invoice->balance * -1, 0, true);
        }

        return $payment;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completePayment(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        $payment = PaymentFactory::create($request->customer_id, $customer->user->id, $customer->account->id);
        $payment->customer_id = $customer->id;
        $payment->company_gateway_id = $request->company_gateway_id;
        $payment->status_id = Payment::STATUS_COMPLETED;
        $payment->date = Carbon::now();
        $payment->amount = $request->amount;
        $payment->type_id = $request->payment_type;
        $payment->transaction_reference = $request->payment_method;
        $payment->save();

        $this->attachInvoices($customer, $payment, $request->ids);

        event(new PaymentWasCreated($payment, $payment->account));

        return response()->json(['code' => 200, 'payment_id' => $payment->id]);
    }
}
