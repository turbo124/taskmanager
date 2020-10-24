
<?php

namespace App\Components\InvoiceCalculator;

use App\Models\Credit;

/**
 * Class LineItem
 * @package App\Components\InvoiceCalculator
 */
class GatewayCalculator extends BaseCalculator
{
    /**
     * @var float
     */
    private $min_value = 0.00;

    /**
     * @var float
     */
    private float $max_value = 0.00;

    /**
     * @var int
     */
    private $fee_amount = 0;

    /**
     * @var bool
     */
    private $fee_percent = true;

    /**
     * @var float
     */
    private $tax_total = 0.00;

    /**
     * GatewayCalculator constructor.
     * @param $entity
     */
    public function __construct($entity = null)
    {
        parent::__construct($entity);
    }

    public function build()
    {
        $this->calculateAmount();
        $this->calculateTax();
        return $this;
    }

    public function calculateAmount(): self
    {
        $this->total += $this->fee_amount;
        return $this;
    }

    /**
     * @return $this
     */
    private function calculateTax(): self
    {
        $this->tax_total += $this->applyTax($this->total, $this->tax_rate, $this->is_amount_discount);

        if ($this->tax_2 && $this->tax_2 > 0) {
            $this->tax_total += $this->applyTax($sub_total, $this->tax_2, $this->is_amount_discount);
        }
        if ($this->tax_3 && $this->tax_3 > 0) {
            $this->tax_total += $this->applyTax($sub_total, $this->tax_3, $this->is_amount_discount);
        }

        $this->total += $this->tax_total;

        return $this;
    }
   

    public function toObject()
    {
        return (object)[
            'tax_rate_name'      => $this->getTaxRateName(),
            'tax_rate_id'        => $this->getTaxRateId(),
            'min_value'          => $this->getMinValue(),
            'max_value'          => $this->getMaxValue(),
            'fee_amount'         => $this->getFeeAmount(),
            'fee_percent'        => $this->getFeePercent(),
            'tax_total'          => $this->getTaxTotal()
        ];
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->tax_rate;
    }

    /**
     * @param float $tax_rate
     */
    public function setTaxRate($name, $tax_rate): self
    {
        $this->{$name} = $tax_rate;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxRateName(): string
    {
        return $this->tax_rate_name;
    }

    /**
     * @return int
     */
    public function getMinValue(): float
    {
        return $this->min_value;
    }

    /**
     * @param float $unit_price
     * @return $this
     */
    public function setMinValue(float $min_value): self
    {
        $this->max_value = $min_value;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxValue(): float
    {
        return $this->max_value;
    }

    /**
     * @param float $unit_price
     * @return $this
     */
    public function setMaxValue(float $max_value): self
    {
        $this->max_value = $max_value;
        return $this;
    }

    /**
     * @return int
     */
    public function getFeeAmount(): float
    {
        return $this->fee_amount;
    }

    /**
     * @param float $unit_price
     * @return $this
     */
    public function setFeeAmount(float $fee_amount): self
    {
        $this->fee_amount = $fee_amount;
        return $this;
    }

    public function setFeePercent(bool $fee_percent = true): self
    {
        $this->fee_percent = $fee_percent;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubTotal(): float
    {
        return $this->sub_total;
    }

    /**
     * @param float $sub_total
     * @return $this
     */
    public function setSubTotal(float $sub_total): self
    {
        $this->sub_total = $sub_total;
        return $this;
    }


    /**
     * @param string $tax_rate_name
     */
    public function setTaxRateName(string $tax_rate_name): self
    {
        $this->tax_rate_name = $tax_rate_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxRateId(): ?int
    {
        return $this->tax_rate_id;
    }
}
