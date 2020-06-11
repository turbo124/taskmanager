<?php


namespace App\Helpers\Payment;


use App\Payment;
use App\Repositories\PaymentRepository;

class ProcessPayment
{

    /**
     * @param Payment $payment
     * @param array $data
     * @param PaymentRepository $payment_repo
     * @return Payment|bool
     */
    public function process(array $data, PaymentRepository $payment_repo, Payment $payment)
    {
        $payment = $payment_repo->save($data, $payment);
        
        $objCreditPayment = null;
 
        if(!empty($data['credits'])) {
            $objCreditPayment = new CreditPayment($data, $payment, $payment_repo);
            $objCreditRefunds->process();
        }

        if (!empty($data['invoices'])) {
            return (new InvoicePayment($data, $payment, $payment_repo))->process($objCreditRefunds);
        }
    }
}
