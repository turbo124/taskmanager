<?php


namespace App\Transformations;


use App\AttributeValue;
use App\ProductAttribute;

class ProductAttributeTransformable
{
    /**
     * @param ProductAttribute $product_attribute
     * @return array
     */
    public function transformProductAttribute(ProductAttribute $product_attribute)
    {

        return [
            'id'          => (int)$product_attribute->id,
            'product_id'  => (int)$product_attribute->product_id,
            'quantity'    => (int)$product_attribute->quantity,
            'price'       => (float)$product_attribute->price,
            'values'      => $this->transformAttributeValues($product_attribute->attributesValues),
            'created_at'  => $product_attribute->created_at,
            'updated_at'  => $product_attribute->updated_at,
            'archived_at' => $product_attribute->deleted_at,
        ];
    }

    /**
     * @param $attributeValues
     * @return array
     */
    private function transformAttributeValues($attributeValues)
    {
        if ($attributeValues->count() === 0) {
            return [];
        }

        return $attributeValues->map(function (AttributeValue $attribute_value) {
            return (new AttributeValueTransformable())->transformAttributeValue($attribute_value);
        })->all();
    }
}