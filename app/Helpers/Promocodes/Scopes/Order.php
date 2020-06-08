<?php

namespace App\Helpers\Promocodes\Scopes;

class Order implements ScopeInterface
{
    /**
     * @var Order
     */
    private \App\Order $order;

    private $scope_value;

    /**
     * @param mixed $scope_value
     */
    public function setScopeValue($scope_value): self
    {
        $this->scope_value = $scope_value;
        return $this;
    }

    public function setOrder(\App\Order $order) : self
    {
        $this->order = $order;
        return $this;
    }


    public function validate(): bool
    {
        if ($this->order->total < $this->scope_value) {
            return false;
        }

        return true;
    }
}