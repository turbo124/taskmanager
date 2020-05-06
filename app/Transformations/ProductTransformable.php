<?php

namespace App\Transformations;

use App\Product;

trait ProductTransformable
{

    /**
     * @param Product $product
     * @return array
     */
    protected function transformProduct(Product $product)
    {
        $attributes = $product->attributes->first();

        $range_from = $range_to = $payable_months = $minimum_downpayment = $number_of_years = $interest_rate = 0;

        if ($attributes && $attributes->count() > 0) {
            $range_from = $attributes->range_from;
            $range_to = $attributes->range_to;
            $payable_months = $attributes->payable_months;
            $minimum_downpayment = $attributes->minimum_downpayment;
            $number_of_years = $attributes->number_of_years;
            $interest_rate = $attributes->interest_rate;
        }

        return [
            'id'                  => (int)$product->id,
            'name'                => $product->name,
            'sku'                 => $product->sku,
            'quantity'            => $product->quantity,
            'created_at'          => $product->created_at,
            'deleted_at'          => $product->deleted_at,
            'slug'                => $product->slug,
            'description'         => $product->description,
            'cost'                => (float)$product->cost ?: 0,
            'user_id'             => (int)$product->user_id,
            'assigned_user_id'    => (int)$product->assigned_user_id,
            'notes'               => $product->notes ?: '',
            'price'               => (float)$product->price ?: 0,
            'quantity'            => (float)$product->quantity ?: 1.0,
            'status'              => $product->status,
            'cover'               => $product->cover,
            'company_id'          => (int)$product->company_id,
            'brand'               => !empty($product->company) ? $product->company->name : null,
            'category_ids'        => $product->categories()->pluck('category_id')->all(),
            'images'              => $product->images()->get(['src']),
            'custom_value1'       => $product->custom_value1 ?: '',
            'custom_value2'       => $product->custom_value2 ?: '',
            'custom_value3'       => $product->custom_value3 ?: '',
            'custom_value4'       => $product->custom_value4 ?: '',
            'range_from'          => $range_from,
            'range_to'            => $range_to,
            'payable_months'      => $payable_months,
            'minimum_downpayment' => $minimum_downpayment,
            'number_of_years'     => $number_of_years,
            'interest_rate'       => $interest_rate
        ];

    }

}
