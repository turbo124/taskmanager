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
            'custom_value4'       => $product->custom_value4 ?: ''
        ];

    }

}
