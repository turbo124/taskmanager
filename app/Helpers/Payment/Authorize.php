<?php


namespace App\Helpers\Payment;


use App\CompanyGateway;
use App\Invoice;
use App\Jobs\Payment\CreatePayment;
use App\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CustomerProfilePaymentType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\PaymentProfileType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;

class Authorize
{
    private $customer_gateway;

    private $company_gateway;

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build($amount)
    {
        error_reporting(E_ALL & ~E_DEPRECATED);

        $this->getCustomerGateway();
        $this->getCompanyGateway();
        $this->chargeCustomerProfile($amount);
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

    private function getCompanyGateway()
    {
        $this->company_gateway = CompanyGateway::where('id', 6)->first();
        return true;
    }

    private function getCustomerGateway()
    {
        $this->customer_gateway = DB::table('client_gateway_tokens')
                                   ->where('account_id', $this->invoice->account_id)
                                   ->where('customer_id', $this->invoice->customer_id)
                                   ->where('company_gateway_id', 6)
                                   ->first();

        return true;
    }

    private function chargeCustomerProfile($amount)
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
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new CreateTransactionRequest();
        $request->setMerchantAuthentication($config);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null)
        {
            if($response->getMessages()->getResultCode() == "Ok")
            {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null)
                {
                    $this->completePayment($amount, $tresponse);
                    return true;
                }
            }
        }

        return false;
    }

    private function completePayment($amount, $response): ?Payment
    {

        $data = [
            'payment_method'     => $response->getTransId(),
            'payment_type'       => 6,
            'amount'             => $amount,
            'customer_id'        => $this->invoice->customer_id,
            'company_gateway_id' => $this->company_gateway->id,
            'ids'                => $this->invoice->id,
            'order_id'           => null
        ];

        $payment = CreatePayment::dispatchNow($data, (new PaymentRepository(new Payment())));

        return $payment;
    }
}