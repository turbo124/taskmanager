<?php

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;

$factory->define(
    Invoice::class,
    function (Faker\Generator $faker) {
        $customer = factory(Customer::class)->create();
        $user = factory(User::class)->create();

        $total = 800;

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
            'account_id'     => 1,
            'status_id'      => Invoice::STATUS_DRAFT,
            'number'         => $faker->ean13(),
            'total'          => $total,
            'balance'        => $total,
            'tax_total'      => $faker->randomFloat(2),
            'discount_total' => $faker->randomFloat(2),
            'customer_id'    => $customer->id,
            'user_id'        => $user->id,
            'is_deleted'     => false,
            'po_number'      => $faker->text(10),
            'date'           => $faker->date(),
            'due_date'       => $faker->date(),
            'line_items'     => $line_items,
            'terms'          => $faker->text(500),
            'gateway_fee'    => 12.99
        ];
    }
);
