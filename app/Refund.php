<?php

namespace App;

use App\Events\Payment\PaymentWasRefunded;
use App\Events\Credit\CreditWasCreated;
use App\Factory\CreditFactory;
use App\Factory\NotificationFactory;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Repositories\CreditRepository;
use Omnipay\Omnipay;

class Refund
{

    /**
     * @var Payment
     */
    private Payment $payment;

    /**
     * @var CreditRepository
     */
    private CreditRepository $credit_repo;

    private $data;

    /**
     * Refund constructor.
     * @param Payment $payment
     * @param CreditRepository $credit_repo
     * @param array $data
     */
    public function __construct(Payment $payment, CreditRepository $credit_repo, array $data)
    {
        $this->payment = $payment;
        $this->credit_repo = $credit_repo;
        $this->data = $data;
    }
}
