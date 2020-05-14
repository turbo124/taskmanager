<?php

namespace App\Transformations;

use App\Product;
use App\ProductAttribute;

trait ProductTransformable
{

    /**
     * @param Product $product
     * @return array
     */
    protected function transformProduct(Product $product)
    {
        return [
            'id'               => (int)$product->id,
            'name'             => $product->name,
            'sku'              => $product->sku,
            'created_at'       => $product->created_at,
            'deleted_at'       => $product->deleted_at,
            'slug'             => $product->slug,
            'description'      => $product->description,
            'cost'             => (float)$product->cost ?: 0,
            'user_id'          => (int)$product->user_id,
            'assigned_user_id' => (int)$product->assigned_user_id,
            'notes'            => $product->notes ?: '',
            'price'            => (float)$product->price ?: 0,
            'quantity'         => (float)$product->quantity ?: 1,
            'status'           => $product->status,
            'cover'            => $product->cover,
            'company_id'       => (int)$product->company_id,
            'brand'            => !empty($product->company) ? $product->company->name : null,
            'is_featured'      => (bool)$product->is_featured,
            'category_ids'     => $product->categories()->pluck('category_id')->all(),
            'images'           => $product->images()->get(['src']),
            'attributes'       => $product->attributes->count() > 0 ? $this->transformProductAttributes(
                $product->attributes
            ) : [],
            'custom_value1'    => $product->custom_value1 ?: '',
            'custom_value2'    => $product->custom_value2 ?: '',
            'custom_value3'    => $product->custom_value3 ?: '',
            'custom_value4'    => $product->custom_value4 ?: ''
        ];
    }

    private function transformProductAttributes($productAttributes)
    {
        if ($productAttributes->count() === 0) {
            return [];
        }

        return $productAttributes->map(
            function (ProductAttribute $product_attribute) {
                return (new ProductAttributeTransformable)->transformProductAttribute($product_attribute);
            }
        )->all();
    }

}
