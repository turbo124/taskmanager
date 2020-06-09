<?php


namespace App\Helpers\Promocodes\Scopes;


interface ScopeInterface
{

    /**
     * @param $scope_value
     * @return mixed
     */
    public function setScopeValue($scope_value);

    /**
     * @param \App\Order $order
     * @return mixed
     */
    public function setOrder(\App\Order $order);

    /**
     * @return bool
     */
    public function validate(): bool;
}