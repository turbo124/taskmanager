<?php


namespace App\Helpers\Payment\Gateways;


use App\CompanyGateway;
use App\Customer;
use App\Invoice;
use App\Jobs\Payment\CreatePayment;
use App\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;

class BasePaymentGateway
{

    protected $customer_gateway;

    protected $company_gateway;

    /**
     * @var Invoice
     */
    protected Invoice $invoice;

    /**
     * @var Customer
     */
    protected Customer $customer;

    /**
     * BasePaymentGateway constructor.
     * @param Customer $customer
     */
    public function __construct(Customer $customer, $customer_gateway, $company_gateway)
    {
        $this->customer = $customer;
        $this->customer_gateway = $customer_gateway;
        $this->company_gateway = $company_gateway;
    }

    /**
     * @param $amount
     * @param Invoice $invoice
     * @param $transaction_id
     * @return Payment|null
     */
    protected function completePayment($amount, Invoice $invoice, $transaction_id): ?Payment
    {

        $data = [
            'payment_method'     => $transaction_id,
            'payment_type'       => 6,
            'amount'             => $amount,
            'customer_id'        => $this->customer->id,
            'company_gateway_id' => $this->company_gateway->id,
            'ids'                => $invoice->id,
            'order_id'           => null
        ];

        $payment = CreatePayment::dispatchNow($data, (new PaymentRepository(new Payment())));

        return $payment;
    }
}