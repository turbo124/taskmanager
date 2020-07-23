<?php


namespace App\Helpers\Payment\Gateways;


use App\Models\Invoice;
use App\Jobs\Payment\CreatePayment;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Stripe\Customer;
use Stripe\StripeClient;

class Stripe extends BasePaymentGateway
{
    private $stripe;

    /**
     * Stripe constructor.
     * @param \App\Models\Customer $customer
     * @param $customer_gateway
     * @param $company_gateway
     */
    public function __construct(\App\Models\Customer $customer, $customer_gateway, $company_gateway)
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
     * @return \App\Models\Payment|bool|null
     */
    private function createCharge(float $amount, Invoice $invoice = null)
    {
        $currency = $this->customer->currency;
        $credit_card = $this->findCreditCard();

        if (empty($credit_card)) {
            return false;
        }

        $invoice_label = $invoice !== null ? "Invoice: {$invoice->getNumber()}" : '';

        //https://stripe.com/docs/api/errors/handling

        try {
            $response = $this->stripe->paymentIntents->create(
                [
                    'payment_method' => $this->customer_gateway->token,
                    'customer'       => $this->customer_gateway->gateway_customer_reference,
                    'confirm'        => true,
                    'amount'         => $this->convertToStripeAmount($amount, $currency->precision),
                    'currency'       => $currency->code,
                    'description'    => "{$invoice_label} Amount: {$amount} Customer: {$this->customer->name}",
                ]
            );
        } catch (\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            echo 'Status is:' . $e->getHttpStatus() . '\n';
            echo 'Type is:' . $e->getError()->type . '\n';
            echo 'Code is:' . $e->getError()->code . '\n';
            // param is '' in this case
            echo 'Param is:' . $e->getError()->param . '\n';
            echo 'Message is:' . $e->getError()->message . '\n';
            return false;
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            return false;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            return false;
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            return false;
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            return false;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return false;
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            return false;
        }

        $brand = $response->charges->data[0]->payment_method_details->card->brand;
        $payment_method = !empty($this->card_types[$brand]) ? $this->card_types[$brand] : 12;

        if ($invoice !== null) {
            return $this->completePayment($amount, $invoice, $response->charges->data[0]->id, $payment_method);
        }

        return true;
    }
}