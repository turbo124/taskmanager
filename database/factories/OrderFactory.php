<?php

use App\Order;
use App\Customer;
use App\User;

$factory->define(Order::class, function (Faker\Generator $faker) {

    $customer = factory(Customer::class)->create();
    $user = factory(User::class)->create();

    for ($x = 0; $x < 2; $x++) {
        $product = factory(\App\Product::class)->create();

        $line_items[] = (new \App\Helpers\InvoiceCalculator\LineItem)
            ->setQuantity(2)
            ->setUnitPrice(40)
            ->calculateSubTotal()->setUnitDiscount(0)
            ->setUnitTax(0)
            ->setProductId($product->id)
            ->setNotes($faker->realText(50))
            ->toObject();
    }

    return [
        'account_id' => 1,
        'status_id' => Order::STATUS_DRAFT,
        'number' => '',
        'total' => 160,
        'tax_total' => 20,
        'shipping_cost' => 20,
        'discount_total' => 20,
        'customer_id' => $customer->id,
        'user_id' => $user->id,
        'date' => $faker->date(),
        'due_date' => $faker->date(),
        'custom_value1' => $faker->numberBetween(1,4),
        'custom_value2' => $faker->numberBetween(1,4),
        'custom_value3' => $faker->numberBetween(1,4),
        'custom_value4' => $faker->numberBetween(1,4),
        'is_deleted' => false,
        'po_number' => $faker->text(10),
        'line_items' => $line_items,
        'gateway_fee' => 0
    ];
});
