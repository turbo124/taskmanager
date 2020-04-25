<?php

use Faker\Generator as Faker;

$factory->define(App\Credit::class, function (Faker $faker) {
    $customer = factory(\App\Customer::class)->create();
    $user = factory(\App\User::class)->create();

    $line_items = [];

    for ($x = 0; $x < 5; $x++) {
        $line_items[] = (new \App\Helpers\InvoiceCalculator\LineItem)
            ->setQuantity($faker->numberBetween(1, 10))
            ->setUnitPrice($faker->randomFloat(2, 1, 1000))
            ->calculateSubTotal()->setUnitDiscount($faker->numberBetween(1, 10))
            ->setUnitTax(10.00)
            ->setProductId($faker->word())
            ->setNotes($faker->realText(50))
            ->toObject();
    }

    return [
        'customer_id' => $customer->id,
        'status_id'   => App\Credit::STATUS_DRAFT,
        'number'      => $faker->ean13(),
        'account_id'  => 1,
        'user_id'     => $user->id,
        //'discount' => $faker->numberBetween(1,10),
        //'is_amount_discount' => (bool)random_int(0,1),
        'is_deleted'  => false,
        //'po_number' => $faker->text(10),
        'date'        => $faker->date(),
        'due_date'    => $faker->date(),
        'line_items'  => $line_items,
        'terms'       => $faker->text(500),
    ];
});