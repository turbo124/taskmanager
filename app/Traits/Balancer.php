<?php


namespace App\Traits;


trait Balancer
{

    /**
     * @param $amount
     */
    public function reduceBalance($amount)
    {
        $this->balance -= floatval($amount);

        if ($this->balance === 0.0 && get_class($this) === 'App\Invoice') {
            $this->setStatus(self::STATUS_PAID);
        }

        $this->save();
        return $this;
    }

    /**
     * @param float $amount
     * @return float
     */
    public function increaseBalance(float $amount): float
    {
        $balance = $this->balance += $amount;
        return $this->setBalance($balance);
    }

    public function setTotal(float $total)
    {
        $this->total = (float)$total;
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance)
    {
        $this->balance = (float)$balance;
        return $this->balance;
    }
}