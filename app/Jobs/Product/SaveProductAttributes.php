<?php

namespace App\Jobs\Product;

use App\AttributeValue;
use App\Product;
use App\Repositories\AttributeValueRepository;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\ProductAttribute;

class SaveProductAttributes
{
    use Dispatchable;

    protected $data;
    protected Product $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @param ProductRepository $product_repo
     * @param $fields
     * @return bool
     */
    public function handle(ProductRepository $product_repo, $fields): bool
    {
        $variations = json_decode($fields, true);

        $this->product->attributes()->forceDelete();

        foreach ($variations as $variation) {
            $hasDefault = $product_repo->listProductAttributes()->where('default', 1)->count();
            $variation['is_default'] = $variation['is_default'] == 1 && $hasDefault > 0 ? 0 : 1;

            $objProductAttribute = new ProductAttribute();
            $objProductAttribute->fill($variation);

            $productAttribute = $product_repo->saveProductAttributes(
                $objProductAttribute,
                $this->product
            );

            foreach($variation['attribute_values'] as $value) {
                $attribute = (new AttributeValueRepository(new AttributeValue))->find($value);
                $product_repo->saveCombination($productAttribute, $attribute);
            }
        }

        return true;
    }

    /**
     * @param array $data
     *
     * @return
     */
    private function validateFields(array $data)
    {
        $validator = Validator::make(
            $data,
            [
                'productAttributeQuantity' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }
    }
}
