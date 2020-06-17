<?php


namespace App\Helpers\Refund;


use App\ClientGatewayToken;
use App\CompanyGateway;
use App\Payment;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;

class AuthorizeRefund
{
    /**
     * @var Payment
     */
    private Payment $payment;

    /**
     * @var CompanyGateway
     */
    private CompanyGateway $company_gateway;

    /**
     * AuthorizeRefund constructor.
     * @param Payment $payment
     * @param CompanyGateway $company_gateway
     */
    public function __construct(Payment $payment, CompanyGateway $company_gateway)
    {
        $this->payment = $payment;
        $this->company_gateway = $company_gateway;
    }

    public function build()
    {
        error_reporting(E_ALL & ~E_DEPRECATED);

        $this->setupConfig();
        $this->createCreditCard();
        $this->createTransaction();
        $response = $this->sendRequest();

        return $response;
    }

    private function setupConfig()
    {
        $config = $this->company_gateway->config;

        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $this->config = new MerchantAuthenticationType();
        $this->config->setName($config->apiLoginId);
        $this->config->setTransactionKey($config->transactionKey);

        return true;
    }

    private function createTransaction()
    {

        $this->transactionRequest = new TransactionRequestType();
        $this->transactionRequest->setTransactionType("refundTransaction");
        $this->transactionRequest->setAmount(round($this->payment->amount, 2));
        $this->transactionRequest->setPayment($this->payment_data);
        $this->transactionRequest->setRefTransId($this->payment->transaction_reference);

        return true;
    }

    private function createCreditCard()
    {
        $client_gateway = ClientGatewayToken::whereCompanyGatewayId($this->company_gateway->id)->first();
        $meta = json_decode($client_gateway->meta);

        $card_number = $meta->last4 > 4 ? substr($meta->last4, -4) : $meta->last4;

        // Create the payment data for a credit card
        $creditCard = new CreditCardType();
        $creditCard->setCardNumber($card_number);
        $creditCard->setExpirationDate("XXXX");
        $this->payment_data = new PaymentType();
        $this->payment_data->setCreditCard($creditCard);

        return true;
    }

    private function sendRequest()
    {
        // Set the transaction's refId
        $refId = 'ref' . time();

        $request = new CreateTransactionRequest();
        $request->setMerchantAuthentication($this->config);
        $request->setRefId($refId);
        $request->setTransactionRequest($this->transactionRequest);
        $controller = new CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        echo '<pre>';
        print_r($response);
        die;

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    echo "Refund SUCCESS: " . $tresponse->getTransId() . "\n";
                    echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                }

                return true;
            }
        }

        return false;
    }
}