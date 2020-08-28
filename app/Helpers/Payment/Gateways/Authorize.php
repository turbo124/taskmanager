<?php


namespace App\Helpers\Payment\Gateways;


use App\Models\Customer;
use App\Models\ErrorLog;
use App\Models\Invoice;
use App\Models\Payment;
use App\Factory\ErrorLogFactory;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CustomerProfilePaymentType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\PaymentProfileType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;

class Authorize extends BasePaymentGateway
{

    /**
     * Authorize constructor.
     * @param Customer $customer
     * @param $customer_gateway
     * @param $company_gateway
     */
    public function __construct(Customer $customer, $customer_gateway, $company_gateway)
    {
        parent::__construct($customer, $customer_gateway, $company_gateway);
    }

    public function build($amount, Invoice $invoice)
    {
        error_reporting(E_ALL & ~E_DEPRECATED);
        return $this->chargeCustomerProfile($amount, $invoice);
    }

    private function setupConfig()
    {
        $gateway_config = $this->company_gateway->config;

        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $config = new MerchantAuthenticationType();
        $config->setName($gateway_config->apiLoginId);
        $config->setTransactionKey($gateway_config->transactionKey);

        return $config;
    }

    private function chargeCustomerProfile($amount, Invoice $invoice = null): ?Payment
    {
        $config = $this->setupConfig();

        // Set the transaction's refId
        $refId = 'ref' . time();

        $profileToCharge = new CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($this->customer_gateway->gateway_customer_reference);
        $paymentProfile = new PaymentProfileType();
        $paymentProfile->setPaymentProfileId($this->customer_gateway->token);
        $profileToCharge->setPaymentProfile($paymentProfile);
        $transactionRequestType = new TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount((float)$amount);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new CreateTransactionRequest();
        $request->setMerchantAuthentication($config);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(ANetEnvironment::SANDBOX);

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null) {
                    if ($invoice !== null) {
                        return $this->completePayment($amount, $invoice, $tresponse->getTransId());
                    }

                    return null;
                } else {
                    $errors = [];
                    $error_log = ErrorLogFactory::create($this->customer->account, auth()-user(), $this->customer);
                    $tresponse = $response->getTransactionResponse();
                    if($tresponse != null && $tresponse->getErrors() != null)
                    {
                        $errors['data']['error_code'] = $tresponse->getErrors()[0]->getErrorCode();
                        $errors['data']['message'] = $tresponse->getErrors()[0]->getErrorText();                      
                    }
                    else
                    {
                        $errors['data']['error_code'] = $response->getMessages()->getMessage()[0]->getCode();
                        $errors['data']['message'] =  $response->getMessages()->getMessage()[0]->getText();
                    }

                    $error->log->data = $errors['data'];
                    $error_log->error_type = ErrorLog::PAYMENT_FAILURE; 
                    $error_log->error_result = ErrorLog::FAILURE;
                    $error->entity = 'Authorize.net';
                    $error_log->save();
                }
            }
        }

        return null;
    }
}
