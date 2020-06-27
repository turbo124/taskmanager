<?php


namespace App\Helpers\Refund;


use App\CompanyGateway;
use App\Payment;
use App\Repositories\CreditRepository;
use Omnipay\Omnipay;

class GatewayRefund extends BaseRefund
{
    const AUTHORIZE_ID = 6;

    /**
     * GatewayRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repo
     */
    public function __construct(Payment $payment, array $data, CreditRepository $credit_repo)
    {
        parent::__construct($payment, $data, $credit_repo);
        $this->payment = $payment;
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function refund()
    {
        if (empty($this->payment->company_gateway_id)) {
            return false;
        }

        $company_gateway = CompanyGateway::find($this->payment->company_gateway_id);

        if (!$company_gateway) {
            return false;
        }

        if ($company_gateway->id === self::AUTHORIZE_ID) {
            return (new AuthorizeRefund($this->payment, $company_gateway, $this->data))->build();
        }

        return $this->doRefund($company_gateway);
    }

    private function doRefund(CompanyGateway $company_gateway)
    {
        $gateway = Omnipay::create($company_gateway->gateway->provider);

        $gateway->initialize((array)$company_gateway->config);

        $response = $gateway
            ->refund(
                [
                    'transactionReference' => $this->payment->transaction_reference,
                    'amount'               => $this->data['amount'] ?? $this->payment->amount,
                    'currency'             => $this->payment->customer->currency->code
                ]
            )
            ->send();

        if ($response->isSuccessful()) {
            return true;
        }
    }

}