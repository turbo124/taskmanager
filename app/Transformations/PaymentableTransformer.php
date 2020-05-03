<?php

namespace App\Transformations;

use App\Payment;
use App\Paymentable;

class PaymentableTransformer
{
    public function transform(Paymentable $paymentable)
    {
        $entity_key = 'invoice_id';
        if ($paymentable->paymentable_type == Credit::class) {
            $entity_key = 'credit_id';
        }


        return [
        'id' => $paymentable->id,
        $entity_key => $paymentable->paymentable_id,
        'amount' => $paymentable->amount,
        'paymentable_type' => $paymentable->paymentable_type,
        'refunded' => (float)$paymentable->refunded,
        'payment_id' => (int)$paymentable->payment_id,
        ];
    }
}
