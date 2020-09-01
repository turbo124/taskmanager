<?php

namespace App\Helpers\InvoiceCalculator;


class BaseCalculator
{
    private $customer;

    private $decimals = 2;

    /**
     * @var float
     */
    private $line_tax_total = 0.00;

    /**
     * @var float
     */
    private $line_discount_total = 0.00;

    protected $entity;

    public function __construct($entity)
    {
        $this->customer = $entity !== null ? $entity->customer : null;
        $this->decimals = $entity !== null ? $this->customer->currency->precision : 2;
    }

    /**
     * @param float $target
     * @param float $tax
     * @param bool $rate
     * @return false|float
     */
    protected function applyTax(float $total, $tax, bool $rate = false)
    {
        if (empty($tax) || $tax <= 0) {
            return 0;
        }

        if (!$rate) {
            $this->line_tax_total = round($total * ($tax / 100), $this->decimals);
            return $this->line_tax_total;
        }

        $this->line_tax_total = round($tax, $this->decimals);
        return $this->line_tax_total;
    }

    /**
     * @return false|float
     */
    protected function calculateBalance($total, $balance)
    {
        if ($total != $balance) {

            $paid_to_date = $total - $balance;

            return round($total, $this->decimals) - $paid_to_date;
        }

        return round($total, $this->decimals);
    }

    protected function calculateTaxTotal(float $total, float $tax, $inclusive = false)
    {
    }

    /**
     * @param float $target
     * @param float $discount
     * @param bool $rate
     * @return false|float
     */
    protected function applyDiscount(float $total, float $discount, bool $rate = false)
    {
        if ($discount <= 0) {
            return 0;
        }

        if (!$rate) {
            $this->line_discount_total = round($total * ($discount / 100), $this->decimals);
            return $this->line_discount_total;
        }

        $this->line_discount_total = round($discount, $this->decimals);
        return $this->line_discount_total;
    }

    protected function calculateGatewayFee(float $total, float $gateway_fee, bool $is_percentage = false)
    {
        if ($gateway_fee <= 0) {
            return 0;
        }

        if ($is_percentage) {
            $gateway_amount = round($total * ($gateway_fee / 100), $this->decimals);
            return $gateway_amount;
        }

        $gateway_amount = round($gateway_fee, $this->decimals);
        return $gateway_amount;
    }

    /**
     * @param float $price
     * @param float $quantity
     * @return false|float
     */
    protected function applyQuantity(float $price, float $quantity)
    {
        return round($price * $quantity, $this->decimals);
    }

    /**
     * @return float
     */
    public function getLineTaxTotal(): float
    {
        return $this->line_tax_total;
    }

    /**
     * @param float $tax_total
     */
    public function setLineTaxTotal(float $tax_total): self
    {
        $this->line_tax_total = $tax_total;
        return $this;
    }

    /**
     * @return float
     */
    public function getLineDiscountTotal(): float
    {
        return $this->line_discount_total;
    }

    /**
     * @param float $line_discount_total
     */
    public function setLineDiscountTotal(float $line_discount_total): self
    {
        $this->line_discount_total = $line_discount_total;
        return $this;
    }


}
