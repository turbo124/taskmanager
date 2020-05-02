<?php
namespace App\Settings;

class LineItemSettings extends BaseSettings
{
        private $settings = [
            'type_id'            => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'quantity'           => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'unit_price'         => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'product_id'         => ['required' => false, 'default_value' => 1, 'type' => 'int'],
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

    }

}
