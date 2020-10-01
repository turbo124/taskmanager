<?php

use App\Models\Invoice;

$factory->define(
    \App\Components\InvoiceCalculator\LineItem::class, function (Faker\Generator $faker) {
    
     $invoice = factory(Invoice::class)->create();
    
    return [
        'invoice_id' => $invoice->id,
        'quantity' => $faker->randomDigit,
        'unit_discount' => $faker->randomFloat,
        'tax_total' => $faker->randomFloat,
        'unit_tax' => $faker->randomFloat,
        'sub_total' => $faker->randomFloat,
        'unit_price' => $faker->randomFloat,
        'status' => 1
    ];
});