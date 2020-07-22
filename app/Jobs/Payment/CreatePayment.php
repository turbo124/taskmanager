<?php

namespace App\Jobs\Payment;

use App\Customer;
use App\Factory\PaymentFactory;
use App\Invoice;
use App\Order;
use App\Payment;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

/**
 * Class SaveAttributeValues
 * @package App\Jobs\Attribute
 */
class CreatePayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;

    private $ids;

    private Customer $customer;

    /**
     * @var PaymentRepository
     */
    private PaymentRepository $payment_repo;

    /**
     * CreatePayment constructor.
     * @param array $data
     * @param PaymentRepository $payment_repo
     */
    public function __construct(array $data, PaymentRepository $payment_repo)
    {
        $this->data = $data;
        $this->payment_repo = $payment_repo;
    }

    public function handle(): Payment
    {
        if (!empty($this->data['order_id']) && $this->data['order_id'] !== 'null') {
            return $this->createInvoiceFromOrder();
        }

        return $this->createPaymentFromInvoice();
    }

    private function createPayment()
    {
        $payment = PaymentFactory::create($this->customer, $this->customer->user, $this->customer->account);
        $data = [
            'company_gateway_id'    => $this->data['company_gateway_id'],
            'status_id'             => Payment::STATUS_COMPLETED,
            'date'                  => Carbon::now(),
            'amount'                => $this->data['amount'],
            'type_id'               => $this->data['payment_type'],
            'transaction_reference' => $this->data['payment_method']

        ];

        $payment = $this->payment_repo->save($data, $payment);

        return $payment;
    }

    /**
     * @return Payment
     */
    private function createPaymentFromInvoice(): Payment
    {
        $this->ids = $this->data['ids'];
        $this->customer = Customer::find($this->data['customer_id']);
        $payment = $this->createPayment();
        $this->attachInvoices($payment);

        return $payment;
    }

    /**
     * @return Payment
     */
    private function createInvoiceFromOrder(): Payment
    {
        // order to invoice
        $order = Order::where('id', '=', $this->data['order_id'])->first();
        $order = $order->service()->dispatch(new InvoiceRepository(new Invoice), new OrderRepository(new Order));
        $this->ids = $order->invoice_id;
        $this->customer = $order->customer;
        $payment = $this->createPayment();
        $this->attachInvoices($payment);

        return $payment;
    }

    /**
     * @param Customer $customer
     * @param Payment $payment
     * @return Payment
     */
    private function attachInvoices(Payment $payment): Payment
    {
        $invoices = Invoice::whereIn('id', explode(",", $this->ids))
                           ->whereCustomerId($this->customer->id)
                           ->get();

        foreach ($invoices as $invoice) {
            $this->updateCustomer($payment, $invoice);
            $payment->transaction_service()->createTransaction($invoice->balance * -1, $invoice->customer->balance);
            $invoice->reduceBalance($invoice->balance);
            $payment->attachInvoice($invoice);
        }

        return $payment;
    }

    /**
     * @param Payment $payment
     * @param Invoice $invoice
     */
    private function updateCustomer(Payment $payment, Invoice $invoice)
    {
        $payment->customer->increaseBalance($invoice->balance * -1);
        $payment->customer->increasePaidToDateAmount($invoice->balance);
        $payment->customer->save();
    }
}