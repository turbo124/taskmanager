<?php

namespace App\Helpers\InvoiceCalculator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Class Invoice
 * @package App\Helpers\InvoiceCalculator
 */
class Invoice extends BaseCalculator
{
    /**
     * @var float
     */
    private $discount_total = 0.00;

    /**
     * @var float
     */
    private $sub_total = 0.00;

    /**
     * @var float
     */
    private $balance = 0.00;

    /**
     * @var float
     */
    private $tax_total = 0.00;

    /**
     * @var float
     */
    private $total = 0.00;

    /**
     * @var float
     */
    private $custom_tax = 0.00;

    /**
     * @var float
     */
    private $discount_percentage;

    /**
     * @var float
     */
    private $tax_rate;

    /**
     * @var float
     */
    private $taxable_amount;

    /**
     * @var array
     */
    private $line_items = [];

    private $partial = 0;

    /**
     * @var bool
     */
    private $inclusive_taxes = false;

    /**
     * InvoiceCalculator constructor.
     * @param $entity
     */
    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    /**
     * @var bool
     */
    private $is_amount_discount = false;

    public function build()
    {
        $this->total = $this->sub_total;
        $this->calculateCustomValues();
        $this->calculateDiscount();
        $this->calculateTax();

        $this->getCalculatedBalance();
    }

    /**
     * @return false|float
     */
    public function getCalculatedBalance()
    {
        $balance = !$this->entity->id ? $this->total : $this->getBalance();
        $this->balance = $this->calculateBalance($this->total, $balance);

        return $this->balance;
    }

    private function calculateCustomValues()
    {
        $custom_surcharge_total = 0;

        if (!empty($this->entity->transaction_fee)) {
            $custom_surcharge_total += $this->entity->transaction_fee;

//            if (!empty($this->entity->custom_surcharge_tax1)) {
//                $tax_total = $this->applyTax($this->entity->transaction_fee_tax, $this->sub_total, true);
//                $this->setTaxTotal($tax_total);
//                $this->setCustomTax($this->entity->transaction_fee);
//            }
        }

        if (!empty($this->entity->shipping_cost)) {
            $custom_surcharge_total += $this->entity->shipping_cost;

            if (!empty($this->entity->shipping_cost_tax)) {
                $tax_total = $this->applyTax(
                    $this->entity->shipping_cost_tax,
                    $this->sub_total,
                    $this->is_amount_discount
                );
                $this->setTaxTotal($tax_total);
                $this->setCustomTax($this->entity->shipping_cost);
            }
        }

        if ($custom_surcharge_total > 0) {
            $this->total += $custom_surcharge_total;
        }

        if (!empty($this->entity->gateway_fee)) {
            $this->applyGatewayFee();
        }

        if(!empty($this->entity->late_fee_charge)) {
            $this->addChargeToLineItems($this->entity->late_fee_charge, 'Late Fee Charge applied');
        }

        return $this;
    }

    private function applyGatewayFee(): ?bool
    {
        if (empty($this->entity->gateway_fee) || $this->entity->gateway_fee_applied) {
            return true;
        }

        $is_percentage = !empty($this->entity->gateway_percentage) && ($this->entity->gateway_percentage === 'true' || $this->entity->gateway_percentage === true);
        $gateway_fee = $this->calculateGatewayFee($this->total, $this->entity->gateway_fee, $is_percentage);
        $this->addChargeToLineItems($gateway_fee, 'Gateway Fee');
        $this->entity->gateway_fee = $gateway_fee;
        $this->entity->gateway_fee_applied = true;

        if (!empty($this->entity->account->settings->charge_gateway_to_customer) && $this->entity->account->settings->charge_gateway_to_customer === true) {
            $this->total += $gateway_fee;
        }

        return true;
    }

    private function addChargeToLineItems($charge, $description)
    {
        //TODO Change type

        $line_item = (new LineItem);

        $line_item->setQuantity(1)
                  ->setDescription($description)
                  ->setUnitPrice($charge)
                  ->setTypeId($this->entity::GATEWAY_FEE_TYPE)
//                  ->setProductId(null)
                  ->setNotes($description);

        $this->addItem($line_item->toObject());
        return true;
    }

    /**
     * @param int $decimals
     */
    public function calculateDiscount(): self
    {
        $this->total -= $this->discount_total;

        return $this;
    }

    /**
     * @return $this
     */
    public function calculateTax(): self
    {
        $sub_total = $this->custom_tax > 0 ? $this->total + $this->custom_tax : $this->total;
        $this->tax_total += $this->applyTax($sub_total, $this->tax_rate, $this->is_amount_discount);

        if ($this->custom_tax > 0) {
            $this->total = $sub_total;
        }

        $this->total += $this->tax_total;

        return $this;
    }

    /**
     * @param $item
     * @return $this
     */
    public function addItem($item)
    {
        $this->setTaxTotal($item->tax_total);
        $this->setDiscountTotal($item->discount_total);
        $this->setSubTotal($item->line_total);
        $this->line_items[] = $item;

        return $this;
    }

    /**
     * @return array|Collection
     */
    public function getLineItems()
    {
        return $this->line_items;
    }

    /**
     * @return float
     */
    public function getDiscountTotal(): float
    {
        return $this->discount_total;
    }

    /**
     * @param float $discount_total
     * @return $this
     */
    public function setDiscountTotal(float $discount_total): self
    {
        if (!empty($this->discount_total)) {
            return $this;
        }

        $this->discount_total += $discount_total;
        return $this;
    }

    /**
     * @param float $tax_total
     */
    public function setTaxTotal(float $tax_total): self
    {
        $this->tax_total += $tax_total;
        return $this;
    }

    /**
     * @return float
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
        $this->sub_total += $sub_total;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxTotal(): float
    {
        return $this->tax_total;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
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
    public function setTaxRate($tax_rate): self
    {
        if (empty($tax_rate)) {
            return $this;
        }

        $this->tax_rate = $tax_rate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInclusiveTaxes(): bool
    {
        return $this->inclusive_taxes;
    }

    /**
     * @param float $custom_tax
     */
    public function setCustomTax(float $custom_tax): self
    {
        $this->custom_tax += $custom_tax;
        return $this;
    }

    /**
     * @param bool $inclusive_taxes
     */
    public function setInclusiveTaxes(bool $inclusive_taxes): self
    {
        $this->inclusive_taxes = $inclusive_taxes;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance($balance): self
    {
        if (empty($balance)) {
            return $this;
        }

        $this->balance = $balance;
        return $this;
    }

    /**
     * @return int
     */
    public function getPartial(): int
    {
        return $this->partial;
    }

    /**
     * @param int $partial
     */
    public function setPartial($partial): self
    {
        $this->partial =
            max(0, min(round($partial, 2), $this->entity->balance));
        return $this;
    }

    public function rebuildEntity()
    {
        Log::emergency($this->getSubTotal() . ' ' . $this->getTotal());

        $this->entity->sub_total = $this->getSubTotal();
        $this->entity->balance = $this->getBalance();
        $this->entity->total = $this->getTotal();
        $this->entity->tax_total = $this->getTaxTotal();
        $this->entity->discount_total = $this->getDiscountTotal();
        $this->entity->partial = $this->getPartial();
        $this->entity->line_items = $this->getLineItems();
        return $this->entity;
    }
}
