<?php
namespace App\Services;

use App\Helpers\InvoiceCalculator\LineItem;

class ServiceBase
{

    public function calculateTotals($entity)
    {

        if (empty($entity->line_items)) {

            return $entity;
        }

        $objInvoice = new \App\Helpers\InvoiceCalculator\Invoice($entity);

        foreach ($entity->line_items as $line_item) {

            $objLine = (new LineItem($entity))
                ->setQuantity($line_item->quantity)
                ->setUnitPrice($line_item->unit_price)
                ->setProductId($line_item->product_id)
                ->setSubTotal(isset($line_item->sub_total) ? $line_item->sub_total : 0)
                ->setTypeId(!isset($line_item->type_id) ? 1 : $line_item->type_id)
                ->setUnitTax($line_item->unit_tax)
                ->setTaxRateName(isset($line_item->tax_rate_name) ? $line_item->tax_rate_name : '')
                ->setUnitDiscount($line_item->unit_discount)
                ->setIsAmountDiscount(isset($entity->is_amount_discount) ? $entity->is_amount_discount : false)
                ->setInclusiveTaxes($entity->account->settings->inclusive_taxes)
                ->build();


            $objInvoice->addItem($objLine->toObject());
        }

        $objInvoice
            ->setBalance($entity->balance)
            ->setInclusiveTaxes($entity->account->settings->inclusive_taxes)
            ->setTaxRate($entity->tax_rate)
            ->setPartial($entity->partial)
            ->build();
            
       return $objInvoice->getEntity();

    }
}