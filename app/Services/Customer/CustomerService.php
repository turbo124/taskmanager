<?php

namespace App\Services\Customer;

use App\Customer;
use App\Services\ServiceBase;

class CustomerService extends ServiceBase
{
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function updateBalance(float $amount)
    {
        $this->customer->balance += $amount;
        $this->customer->save();

        return $this;
    }

    public function updatePaidToDate(float $amount)
    {
        $this->customer->paid_to_date += $amount;
        $this->customer->save();

        return $this;
    }

    public function save()
    {
        $this->customer->save();

        return $this->customer;
    }
}
