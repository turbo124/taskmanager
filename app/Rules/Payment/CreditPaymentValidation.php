<?php

namespace App\Rules\Payment;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Credit;

class CreditPaymentValidation implements Rule
{

    private $request;

    private $validationFailures = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!isset($this->request['credits'])) {
            return true;
        }

        if (!$this->validate($this->request['credits'])) {
            return false;
        }

        return true;
    }

    private function validate(array $arrCredits)
    {
        $credit_total = 0;
        $this->customer = null;

        foreach ($arrCredits as $arrCredit) {
            $credit = $this->validateCredit($arrCredit['credit_id']);

            if (!$credit) {
                return false;
            }

            if (!$this->validateCustomer($credit)) {
                return false;
            }


            $credit_total += $credit->total;
        }

        if ($credit_total > $this->request['amount']) {
            return false;
        }

        return true;
    }

    private function validateCredit(int $credit_id)
    {
        $credit = Credit::whereId($credit_id)->first();

        // check allowed statuses here
        if (!$credit || $credit->is_deleted) {
            $this->validationFailures[] = 'Credit is not valid';
            return false;
        }

        if ($credit->balance <= 0) {
            $this->validationFailures[] = 'The credit has already been paid';
            return false;
        }

        if (!in_array($credit->status_id, [Credit::STATUS_SENT])) {
            $this->validationFailures[] = 'Credit is at the wrong status';
            return false;
        }

        return $credit;
    }

    private function validateCustomer(Credit $credit)
    {
        if ($this->customer === null) {
            $this->customer = $credit->customer;
            return true;
        }

        if ($this->customer->id !== $credit->customer->id) {
            $this->validationFailures[] = 'Cannot create payment for different customers';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->validationFailures;
    }
}
