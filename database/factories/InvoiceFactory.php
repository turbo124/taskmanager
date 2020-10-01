<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;

$factory->define(
    Invoice::class,
    function (Faker\Generator $faker) {
        $customer = factory(Customer::class)->create();
        $user = factory(User::class)->create();

        $total = 800;

        for ($x = 0; $x < 5; $x++) {
            $line_items[] = (new \App\Components\InvoiceCalculator\LineItem)
                ->setQuantity(1)
                ->setUnitPrice(160)
                ->calculateSubTotal()
                ->setUnitDiscount(0)
                ->setUnitTax(0)
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
            'tax_total'      => 0,
            'discount_total' => 0,
            'customer_id'    => $customer->id,
            'user_id'        => $user->id,
            'is_deleted'     => false,
            'po_number'      => $faker->text(10),
            'date'           => \Carbon\Carbon::now()->format('Y-m-d'),
            'due_date'       => \Carbon\Carbon::now()->addDays(3)->format('Y-m-d'),
            'line_items'     => $line_items,
            'terms'          => $faker->text(500),
            //'gateway_fee'    => 12.99
        ];
    }
);
