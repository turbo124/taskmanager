<?php

namespace App\Settings;

use App\Helpers\InvoiceCalculator\LineItem;

class LineItemSettings extends BaseSettings
{
    private $settings = [
        'type_id'            => ['required' => false, 'default_value' => 1, 'type' => 'int'],
        'quantity'           => ['required' => true, 'default_value' => 1, 'type' => 'float'],
        'unit_price'         => ['required' => true, 'default_value' => 1, 'type' => 'float'],
        'product_id'         => ['required' => true, 'default_value' => 1, 'type' => 'int'],
        'unit_discount'      => ['required' => false, 'default_value' => 1, 'type' => 'float'],
        'is_amount_discount' => ['required' => false, 'default_value' => 1, 'type' => 'bool'],
        'unit_tax'           => ['required' => false, 'default_value' => 1, 'type' => 'float'],
        'tax_total'          => ['required' => false, 'default_value' => 1, 'type' => 'float'],
        'sub_total'          => ['required' => false, 'default_value' => 1, 'type' => 'float'],
        'date'               => ['required' => false, 'default_value' => 1, 'type' => 'string'],
        'custom_value1'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
        'custom_value2'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
        'custom_value3'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
        'custom_value4'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
    ];

    public function save($line_items)
    {
        try {
            $formatted_items = [];

            foreach ($line_items as $line_id => $line_item) {
                $line_item = $this->validate((object)$line_item, $this->settings);

                if (!$line_item) {
                    die('here');
                }

                $item = (new LineItem)
                    ->setTaxRateId(isset($line_item->tax_rate_id) ? $line_item->tax_rate_id : null)
                    ->setQuantity($line_item->quantity)
                    ->setUnitPrice($line_item->unit_price)
                    ->setUnitDiscount($line_item->unit_discount)
                    ->setUnitTax($line_item->unit_tax)
                    ->setProductId($line_item->product_id)
                    ->setSubTotal($line_item->sub_total)
                    ->setTotal($line_item->sub_total)
                    ->setIsAmountDiscount(
                        isset($line_item->is_amount_discount) ? $line_item->is_amount_discount : false
                    )
                    ->setTaxRateName(!empty($line_item->tax_rate_name) ? $line_item->tax_rate_name : '')
                    ->setNotes(!empty($line_item->notes) ? $line_item->notes : '')
                    ->setDescription(!empty($line_item->description) ? $line_item->description : '')
                    ->toObject();

                $formatted_items[$line_id] = $item;
            }

            return $formatted_items;
        } catch (\Exception $e) {
            echo $e->getMessage();
            die('here');
        }
    }

}
