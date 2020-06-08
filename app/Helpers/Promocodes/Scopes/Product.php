<?php

namespace App\Helpers\Promocodes\Scopes;

use App\Order;

class Product implements ScopeInterface
{

    /**
     * @var Order
     */
    private Order $order;

    private $scope_value;

    /**
     * @param mixed $scope_value
     */
    public function setScopeValue($scope_value): void
    {
        $this->scope_value = $scope_value;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    public function validate()
    {
        if (!in_array($this->scope_value, array_column($this->order->line_items, 'product_id'))) {
            return false;
        }

        return true;
    }
}