<?php


namespace App\Helpers\Promocodes\Scopes;


interface ScopeInterface
{

    /**
     * @param $scope_value
     * @return $this
     */
    public function setScopeValue($scope_value): self;

    /**
     * @param \App\Order $order
     * @return $this
     */
    public function setOrder(\App\Order $order): self;

    /**
     * @return bool
     */
    public function validate(): bool;
}