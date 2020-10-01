<?php

use App\Models\Customer;
use App\Models\User;

$factory->define(
    \App\Models\RecurringQuote::class,
    function (Faker\Generator $faker) {
        $customer = factory(Customer::class)->create();
        $user = factory(User::class)->create();

        for ($x = 0; $x < 5; $x++) {
            $line_items[] = (new \App\Components\InvoiceCalculator\LineItem)
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
            'status_id'      => \App\Models\RecurringQuote::STATUS_DRAFT,
            'number'         => '',
            'total'          => $faker->randomFloat(),
            'tax_total'      => $faker->randomFloat(),
            'discount_total' => $faker->randomFloat(),
            'customer_id'    => $customer->id,
            'user_id'        => $user->id,
            'date'           => $faker->date(),
            'due_date'       => \Carbon\Carbon::today()->addDays(5),
            'custom_value1'  => $faker->numberBetween(1, 4),
            'custom_value2'  => $faker->numberBetween(1, 4),
            'custom_value3'  => $faker->numberBetween(1, 4),
            'custom_value4'  => $faker->numberBetween(1, 4),
            'is_deleted'     => false,
            'po_number'      => $faker->text(10),
            'line_items'     => $line_items,
            'frequency'      => 1,
            'start_date'     => $faker->date(),
            'expiry_date'    => $faker->date()
        ];
    }
);
