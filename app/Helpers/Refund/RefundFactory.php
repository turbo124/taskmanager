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
            $refund = (new GatewayRefund($payment, $data, $credit_repo))->refund();

            if (!$refund) {
                return false;
            }
        }

        $objCreditRefunds = null;

        if (!empty($data['credits'])) {
            $objCreditRefunds = new CreditRefund($payment, $data, $credit_repo, $data['credits']);
            $payment = $objCreditRefunds->refund();

            if(empty($data['invoices'])) {
                return $payment;
            }
        }

        if (!empty($data['invoices'])) {
            return (new InvoiceRefund($payment, $data, $credit_repo, $data['invoices']))->refund($objCreditRefunds);
        }

        return (new PaymentRefund($payment, $data, $credit_repo))->refund();
    }
}
