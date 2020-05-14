<?php

namespace App\Helpers\InvoiceCalculator;

/**
 * Class LineItem
 * @package App\Helpers\InvoiceCalculator
 */
class LineItem extends BaseCalculator
{
    /**
     * @var int
     */
    private $sub_total = 0;

    private $total = 0;

    /**
     * @var int
     */
    private $line_item = 0;

    /**
     * @var int
     */
    private $quantity = 0;

    /**
     * @var int
     */
    private $type_id = 1;

    /**
     * @var float
     */
    private $unit_price = 0.00;

    /**
     * @var bool
     */
    private $is_amount_discount = true;

    /**
     * @var bool
     */
    private $inclusive_taxes = false;

    private $tax_rate_name = '';

    private $tax_rate_id = 0;

    private $description = '';

    /**
     * @var float
     */
    private $unit_discount = 0.00;

    /**
     * @var float
     */
    private $unit_tax = 0.00;

    /**
     * @var float
     */
    private $discount_total = 0.00;

    /**
     * @var string
     */
    private $product_id = '';

    /**
     * @var string
     */
    private $notes = '';

    /**
     * @var float
     */
    private $tax_total = 0.00;

    /**
     * LineItem constructor.
     * @param $entity
     */
    public function __construct($entity = null)
    {
        parent::__construct($entity);
    }

    public function build()
    {
        $this->calculateSubTotal();
        $this->calculateDiscount();
        $this->calculateTax();
        return $this;
    }

    public function calculateSubTotal(): self
    {
        $this->sub_total = $this->applyQuantity($this->unit_price, $this->quantity);
        $this->total += $this->sub_total;
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
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): float
    {
        return $this->unit_price;
    }

    /**
     * @param bool $is_amount_discount
     */
    public function setIsAmountDiscount(bool $is_amount_discount = false): self
    {
        $this->is_amount_discount = $is_amount_discount;
        return $this;
    }

    /**
     * @param bool $inclusive_taxes
     * @return $this
     */
    public function setInclusiveTaxes(bool $inclusive_taxes): self
    {
        $this->inclusive_taxes = $inclusive_taxes;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param float $unit_price
     * @return $this
     */
    public function setUnitPrice(float $unit_price): self
    {
        $this->unit_price = $unit_price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitDiscount()
    {
        return $this->unit_discount;
    }

    /**
     * @param float $unit_discount
     */
    public function setUnitDiscount(float $unit_discount): self
    {
        $this->unit_discount = $unit_discount;
        return $this;
    }

    /**
     * @param float $unit_tax
     */
    public function setUnitTax(float $unit_tax): self
    {
        $this->unit_tax = $unit_tax;
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

    /**
     * @param string $tax_rate_name
     */
    public function setTaxRateId($tax_rate_id): self
    {
        $this->tax_rate_id = $tax_rate_id;
        return $this;
    }

    /**
     * @param int $decimals
     */
    public function calculateDiscount(): self
    {
        $this->total -= $this->applyDiscount($this->sub_total, $this->unit_discount, $this->is_amount_discount);

        return $this;
    }

    /**
     * @return $this
     */
    public function calculateTax(): self
    {
        $this->tax_total += $this->applyTax($this->sub_total, $this->unit_tax, true);

        if ($this->inclusive_taxes) {
            $this->total += $this->tax_total;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->product_id;
    }

    /**
     * @param string $product_id
     */
    public function setProductId(string $product_id): self
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type_id;
    }

    /**
     * @param int $type_id
     */
    public function setTypeId(int $type_id): self
    {
        $this->type_id = $type_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes(string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return float
     */
    public function getUnitTax(): float
    {
        return $this->unit_tax;
    }

    /**
     * @return bool
     */
    public function isAmountDiscount(): bool
    {
        return $this->is_amount_discount;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function toObject()
    {
        return (object)[
            'custom_value1'      => '',
            'custom_value2'      => '',
            'custom_value3'      => '',
            'custom_value4'      => '',
            'tax_rate_name'      => $this->getTaxRateName(),
            'tax_rate_id'        => $this->getTaxRateId(),
            'type_id'            => $this->getTypeId(),
            'quantity'           => $this->getQuantity(),
            'notes'              => $this->getNotes(),
            'unit_price'         => $this->getUnitPrice(),
            'unit_discount'      => $this->getUnitDiscount(),
            'unit_tax'           => $this->getUnitTax(),
            'sub_total'          => $this->getTotal(),
            'line_total'         => $this->getSubTotal(),
            'discount_total'     => $this->getLineDiscountTotal(),
            'tax_total'          => $this->getLineTaxTotal(),
            'is_amount_discount' => $this->isAmountDiscount(),
            'product_id'         => $this->getProductId(),
            'description'        => $this->getDescription()
        ];
    }
}
