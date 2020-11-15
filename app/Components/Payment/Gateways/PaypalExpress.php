<?php

namespace App\Components\Payment\Gateways;


use App\Factory\ErrorLogFactory;
use App\Models\Customer;
use App\Models\ErrorLog;
use App\Models\Invoice;
use App\Models\Payment;
use Omnipay\Omnipay;

class PaypalExpress extends BasePaymentGateway
{

    /**
     * PaypalExpress constructor.
     * @param Customer $customer
     * @param $customer_gateway
     * @param $company_gateway
     */
    public function __construct(Customer $customer, $customer_gateway, $company_gateway)
    {
        parent::__construct($customer, $customer_gateway, $company_gateway);
        error_reporting(E_ALL & ~E_DEPRECATED);
    }

    private function setupConfig()
    {
        $this->gateway = Omnipay::create($company_gateway->gateway->provider);

        $this->gateway->initialize((array)$company_gateway->config);
    }

    public function capturePayment(Payment $payment)
    {
        $this->setupConfig();
        $ref = $payment->transaction_reference;

        // then later, when you want to capture it
        $data = array(
            'transactionReference' => $ref,
            'amount' => $payment->amount // pass original amount, or can be less
        );
        
        $response = $this->gateway->capture($data)->send();

        if ($response->isSuccessful()) {
            // success
        } else {
            // error, maybe you took too long to capture the transaction
            $errors['data']['message'] = $response->getMessage();
            $this->addErrorToLog($payment->user, $errors);
        }
    }

    public function authorizePayment(Invoice $invoice)
    {
         $this->setupConfig();

         $data = array(
            'amount' => $invoice->total,
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        );

        $response = $this->gateway->authorize($data)->send();
  
        if ($response->isSuccessful()) {
            // success
        } else {
            // error, maybe you took too long to capture the transaction
            $errors['data']['message'] = $response->getMessage();
            $this->addErrorToLog($invoice->user, $errors);
        }
    }

    private function addErrorToLog(User $user, array $errors)
    {
        $error_log = ErrorLogFactory::create($this->customer->account, $user, $this->customer);
        $error_log->data = $errors['data'];
        $error_log->error_type = ErrorLog::PAYMENT;
        $error_log->error_result = ErrorLog::FAILURE;
        $error_log->entity = 'authorize';

        $error_log->save();

        return true;
    }
}
