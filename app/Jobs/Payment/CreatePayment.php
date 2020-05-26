<?php

namespace App\Jobs\Payment;

use App\Customer;
use App\Events\Payment\PaymentFailed;
use App\Events\Payment\PaymentWasCreated;
use App\Factory\PaymentFactory;
use App\Invoice;
use App\Order;
use App\Payment;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
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

    private $request;

    /**
     * CreatePayment constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(): Payment
    {
        $customer = Customer::find($this->request->customer_id);
        $payment = PaymentFactory::create($customer, $customer->user, $customer->account);
        $payment->customer_id = $customer->id;
        $payment->company_gateway_id = $this->request->company_gateway_id;
        $payment->status_id = Payment::STATUS_COMPLETED;
        $payment->date = Carbon::now();
        $payment->amount = $this->request->amount;
        $payment->type_id = $this->request->payment_type;
        $payment->transaction_reference = $this->request->payment_method;
        $payment->save();

        if(!$payment) {
            event(new PaymentFailed($payment));
        }

        $ids = $this->request->ids;

        if (!empty($this->request->order_id) && $this->request->order_id !== 'null') {
            // order to invoice
            $order = Order::where('id', '=', $this->request->order_id)->first();
            $order = $order->service()->dispatch(new InvoiceRepository(new Invoice), new OrderRepository(new Order));
            $invoice = Invoice::where('id', '=', $order->invoice_id)->first();
            $ids = $invoice->id;
        }

        $this->attachInvoices($customer, $payment, $ids);

        event(new PaymentWasCreated($payment, $payment->account));

        return $payment;
    }

    /**
     * @param Customer $customer
     * @param Payment $payment
     * @param $ids
     * @return Payment
     */
    private function attachInvoices(Customer $customer, Payment $payment, $ids): Payment
    {
        $invoices = Invoice::whereIn('id', explode(",", $ids))
                           ->whereCustomerId($customer->id)
                           ->get();

        foreach ($invoices as $invoice) {
            $payment->attachInvoice($invoice);
            $payment->ledger()->updateBalance($invoice->balance * -1);
            $payment->customer->increaseBalance($invoice->balance * -1);
            $payment->customer->increasePaidToDateAmount($invoice->balance);
            $payment->customer->save();

            $invoice->resetPartialInvoice($invoice->balance * -1, 0, true);
        }

        return $payment;
    }
}