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
    public function process(array $data, PaymentRepository $payment_repo)
    {
    }
}
