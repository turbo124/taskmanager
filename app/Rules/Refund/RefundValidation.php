<?php

namespace App\Rules\Refund;

use Illuminate\Contracts\Validation\Rule;
use App\Payment;
use App\Paymentables;

class RefundValidation implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if(!isset($this->request['id'])) {
            return false;
        }

        if(!$this->validatePayment()) {
            return false;
        }

       return true;
    }


    private function validatePayment() {
        $payment = Payment::whereId($this->request['id'])->first();

        if(!$payment) {
            $this->validationFailures[] = 'Invalid payment';
            return false;
        }

        if($this->request['amount'] > $payment->amount) {
             $this->validationFailures[] = 'Refund amount is to high';
            return false;
        }

        if($payment->status_id !== Payment::STATUS_COMPLETED) {
            $this->validationFailures[] = 'payment has not been completed';
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
