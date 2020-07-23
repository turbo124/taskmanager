<?php

namespace App\Rules\Payment;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Credit;

class ValidAmount implements Rule
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
        return $this->validate();
    }

    private function validate()
    {
        $total = 0;

        if (!empty($this->request['invoices'])) {
            $total += array_sum(array_column($this->request['invoices'], 'amount'));
        }

        if (!empty($this->request['credits'])) {
            $total += array_sum(array_column($this->request['credits'], 'amount'));
        }

        return $this->request['amount'] <= $total;
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
