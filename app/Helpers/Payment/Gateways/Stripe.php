<?php


namespace App\Helpers\Payment\Gateways;


use App\Models\Invoice;
use App\Models\Payment;
use Exception;
use Stripe\Customer;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\RateLimitException;
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

        //https://stripe.com/docs/api/errors/handling
        $errors = [];

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
        } catch (CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $errors['error_status'] = $e->getHttpStatus();
            $errors['error_type'] = $e->getError()->type;
            $errors['error_code'] = $e->getError()->code;
            // param is '' in this case
            $errors['param'] = $e->getError()->param;
            $errors['error_message'] = $e->getError();
           
        } catch (RateLimitException $e) {
            // Too many requests made to the API too quickly
             $errors['error_status'] = $e->getHttpStatus();
            $errors['error_type'] = $e->getError()->type;
            $errors['error_code'] = $e->getError()->code;
            // param is '' in this case
            $errors['param'] = $e->getError()->param;
           
            $errors['error_message'] = 'Too many requests made to the API too quickly';
            return false;
        } catch (InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
             $errors['error_status'] = $e->getHttpStatus();
            $errors['error_type'] = $e->getError()->type;
            $errors['error_code'] = $e->getError()->code;
            // param is '' in this case
            $errors['param'] = $e->getError()->param;
            $errors['error_message'] = 'Invalid parameters were supplied to Stripe's API';
        } catch (AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
             $errors['error_status'] = $e->getHttpStatus();
            $errors['error_type'] = $e->getError()->type;
            $errors['error_code'] = $e->getError()->code;
            // param is '' in this case
            $errors['param'] = $e->getError()->param;
            $errors['error_message'] = 'Authentication with Stripe's API failed';
        } catch (ApiConnectionException $e) {
            // Network communication with Stripe failed
             $errors['error_status'] = $e->getHttpStatus();
            $errors['error_type'] = $e->getError()->type;
            $errors['error_code'] = $e->getError()->code;
            // param is '' in this case
            $errors['param'] = $e->getError()->param;
            $errors['error_message'] = 'Network communication with Stripe failed';
        } catch (ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
             $errors['error_status'] = $e->getHttpStatus();
            $errors['error_type'] = $e->getError()->type;
            $errors['error_code'] = $e->getError()->code;
            // param is '' in this case
            $errors['param'] = $e->getError()->param;
            $errors['error_message'] = 'unexpected error';
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
             $errors['error_status'] = $e->getHttpStatus();
            $errors['error_type'] = $e->getError()->type;
            $errors['error_code'] = $e->getError()->code;
            // param is '' in this case
            $errors['param'] = $e->getError()->param;
            $errors['error_message'] = 'unexpected error';
        }

        if(!empty($errors)) {
            $error_log = ErrorLogFactory::create($this->customer->account, auth()-user(), $this->customer);
            $error->log->data = $errors;
            $error_log->error_type = ErrorLog::PAYMENT_FAILURE; 
            $error_log->error_result = ErrorLog::FAILURE;
            $error->entity = 'Stripe';
            $error_log->save();
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
