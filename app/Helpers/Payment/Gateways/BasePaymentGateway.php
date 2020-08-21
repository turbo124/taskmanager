<?php


namespace App\Helpers\Payment\Gateways;


use App\Jobs\Payment\CreatePayment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\PaymentRepository;

class BasePaymentGateway
{

    protected $customer_gateway;

    protected $company_gateway;

    protected $card_types = [
        'visa'        => 5,
        'master_card' => 6
    ];

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
     * @param int $payment_type
     * @return Payment|null
     */
    protected function completePayment($amount, Invoice $invoice, $transaction_id, $payment_type = 12): ?Payment
    {
        $data = [
            'payment_method'     => $transaction_id,
            'payment_type'       => $payment_type,
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