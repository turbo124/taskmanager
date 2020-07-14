<?php


namespace App\Helpers\Payment\Gateways;


use App\Invoice;
use App\Jobs\Payment\CreatePayment;
use App\Payment;
use App\Repositories\PaymentRepository;
use Stripe\Customer;
use Stripe\StripeClient;

class Stripe extends BasePaymentGateway
{
    private $stripe;

    /**
     * Stripe constructor.
     * @param \App\Customer $customer
     * @param $customer_gateway
     * @param $company_gateway
     */
    public function __construct(\App\Customer $customer, $customer_gateway, $company_gateway)
    {
        parent::__construct($customer, $customer_gateway, $company_gateway);
    }

    /**
     * @param $amount
     * @param Invoice|null $invoice
     * @return Payment|bool|null
     */
    public function build($amount, Invoice $invoice = null)
    {
        $this->setupConfig();
        return $this->createCharge($amount, $invoice);
    }

    private function setupConfig()
    {
        $config = $this->company_gateway->config;

        $this->stripe = new StripeClient(
            $config->apiKey
        );

        return true;
    }

    private function convertToStripeAmount($amount, $precision)
    {
        return $amount * pow(10, $precision);
    }

    private function findCreditCard()
    {
        $stripe_customer = $this->getStripeCustomer();

        $payment_methods = array_filter(
            (array)$stripe_customer->sources['data'],
            function ($var) {
                return ($var->object == 'card');
            }
        );

        if (empty($payment_methods)) {
            return false;
        }

        $payment_method = array_values($payment_methods)[0];

        return $payment_method;
    }

    private function getStripeCustomer(): Customer
    {
        return $this->stripe->customers->retrieve($this->customer_gateway->gateway_customer_reference);
    }

    /**
     * @param float $amount
     * @param Invoice|null $invoice
     * @return Payment|bool|null
     */
    private function createCharge(float $amount, Invoice $invoice = null)
    {
        $currency = $this->customer->currency;
        $credit_card = $this->findCreditCard();

        if (empty($credit_card)) {
            return false;
        }

        $invoice_label = $invoice !== null ? "Invoice: {$invoice->getNumber()}" : '';

        $response = $this->stripe->charges->create(
            [
                'customer'    => $this->getStripeCustomer(),
                'amount'      => $this->convertToStripeAmount($amount, $currency->precision),
                'currency'    => $currency->code,
                'source'      => $credit_card['id'],
                'description' => "{$invoice_label} Amount: {$amount} Customer: {$this->customer->name}",
            ]
        );

        if($invoice !== null) {
            return $this->completePayment($amount, $invoice, $response['id']);
        }

        return true;
    }
}