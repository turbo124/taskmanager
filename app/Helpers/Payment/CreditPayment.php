<?php


namespace App\Helpers\Payment;


use App\Invoice;
use App\Payment;
use App\Paymentable;
use App\Repositories\CreditRepository;

class CreditPayment extends BaseRefund
{
    private array $credits;

    private Payment $payment;

    /**
     * CreditRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repository
     * @param array $payment_credits
     */
    public function __construct($credits, Payment $payment)
    {
        //parent::__construct($payment, $data, $credit_repo);
        $this->credits = $credits;
        $this->payment = $payment;
    }

    public function process()
    {
        $credits = Credit::whereIn('id', array_column($this->credits, 'credit_id'))->get();
        $payment_credits = collect($this->credits)->keyBy('credit_id')->toArray();
       
        foreach ($this->credits as $credit) {
            if (empty($data['credits'][$credit->id])) {
                continue;
            }

            $this->payment->attachCredit($credit);
            $amount = $payment_credits[$credit->id]['amount'];
            $this->updateCredits($credit, $amount);
            $this->increaseAmount($amount);
        }

        return $this;
    }

    /**
     * @param Credit $credit
     * @param Payment $payment
     * @param $amount
     */
    private function updateCredits(Credit $credit, $amount)
    {
        $credit_balance = $credit->balance;
        $status = $amount == $credit_balance ? Credit::STATUS_APPLIED : Credit::STATUS_PARTIAL;
        $credit->setStatus($status);
        $balance = floatval($amount * -1);
        $credit->setBalance($credit_balance + $balance);
        $credit->save();

        return true;
    }
}
