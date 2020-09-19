<?php


namespace App\Helpers\Refund;


use App\Models\CompanyGateway;
use App\Models\Payment;
use App\Repositories\CreditRepository;
use Stripe\StripeClient;

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
        //https://stripe.com/docs/api/refunds/object

        $stripe = new StripeClient(
            $company_gateway->config->apiKey
        );

        $response = $stripe->refunds->create(
            [
                'charge' => $this->payment->transaction_reference,
            ]
        );

        if ($response->status == $response::STATUS_SUCCEEDED) {
            $this->payment->transaction_reference = $response->charge;
            $this->payment->save();

            return true;
        }

        return false;
    }
}