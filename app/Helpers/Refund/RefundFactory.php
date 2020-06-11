<?php


namespace App\Helpers\Refund;


use App\Payment;
use App\Repositories\CreditRepository;

class RefundFactory
{

    /**
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repo
     * @return Payment|bool
     */
    public function createRefund(Payment $payment, array $data, CreditRepository $credit_repo)
    {
        if (!empty($payment->company_gateway_id)) {
            $refund = (new GatewayRefund($payment, $data, $credit_repo));

            if(!$refund) {
                return false;
            }
        }

        if (!empty($data['invoices'])) {
            return (new InvoiceRefund($payment, $data, $credit_repo))->refund($data['invoices']);
        }

        return (new PaymentRefund($payment, $data, $credit_repo))->refund();
    }
}