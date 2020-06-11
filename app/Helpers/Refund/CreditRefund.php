<?php


namespace App\Helpers\Refund;


use App\Invoice;
use App\Payment;
use App\Paymentable;
use App\Repositories\CreditRepository;

class CreditRefund extends BaseRefund
{
    private array $payment_credits;

    /**
     * CreditRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repository
     * @param array $payment_credits
     */
    public function __construct(Payment $payment, array $data, CreditRepository $credit_repo, $payment_credits)
    {
        parent::__construct($payment, $data, $credit_repo);
        $this->payment_credits = $payment_credits;
    }

    public function refund()
    {
        foreach ($this->payment_credits as $payment_credit) {
            $total = $this->getAmount();
            $available_credit = $payment_credit->pivot->amount - $payment_credit->pivot->refunded;
            $total_to_credit = $available_credit > $total ? $total : $available_credit;
            
            $this->updateRefundedAmountForCredit($payment_credit, $total_to_credit);
        }

        $this->save();

        return $this->payment;
    }

    /**
     * @param Invoice $invoice
     * @param $amount
     * @return bool
     */
    private function updateRefundedAmountForCredit($credit, $amount): bool
    {
        $credit->pivot->refunded += $amount;
        $credit->pivot->save();

        $credit->increaseBalance($amount);
        $credit->save();
        return true;
    }
}
