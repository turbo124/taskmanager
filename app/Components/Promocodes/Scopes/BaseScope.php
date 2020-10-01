<?php


namespace App\Components\Promocodes\Scopes;


class BaseScope
{
    /**
     * @var Order
     */
    protected \App\Models\Order $order;

    protected $scope_value;

    /**
     * @param mixed $scope_value
     */
    public function setScopeValue($scope_value)
    {
        $this->scope_value = $scope_value;
        return $this;
    }

    public function setOrder(\App\Models\Order $order)
    {
        $this->order = $order;
        return $this;
    }
}