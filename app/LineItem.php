<?php

namespace App;

class LineItem
{
    /**
     * @var int
     */
    private $quantity = 1;

    private $product_id = '';

    /**
     * @var string
     */
    private $notes = '';

    /**
     * @var float
     */
    private $unit_price = 0.00;

    /**
     * @var float
     */
    private $sub_total = 0.00;

    /**
     * @var float
     */
    private $unit_discount = 0.00;

    /**
     * @var float
     */
    private $unit_tax = 0.00;

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->product_id;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unit_price;
    }

    /**
     * @return float
     */
    public function getSubTotal(): float
    {
        return $this->sub_total;
    }

    /**
     * @return float
     */
    public function getUnitDiscount(): float
    {
        return $this->unit_discount;
    }

    /**
     * @return float
     */
    public function getUnitTax(): float
    {
        return $this->unit_tax;
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
     * @param float $line_total
     */
    public function setSubTotal(float $line_total): self
    {
        $this->sub_total = $line_total;
        return $this;
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
     * @param string $notes
     */
    public function setNotes(string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @param float $unit_price
     */
    public function setUnitPrice(float $unit_price): self
    {
        $this->unit_price = $unit_price;
        return $this;
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

    public function calculateSubTotal(): self
    {
        $this->sub_total = $this->quantity * $this->unit_price;
        return $this;
    }

    public function toObject()
    {
        return (object)[
            'quantity'      => $this->getQuantity(),
            'notes'         => $this->getNotes(),
            'unit_price'    => $this->getUnitPrice(),
            'unit_discount' => $this->getUnitDiscount(),
            'unit_tax'      => $this->getUnitTax(),
            'sub_total'     => $this->getSubTotal()
        ];
    }
}