<?php

namespace App\Jobs\Product;

use App\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\ProductAttribute;

class SaveProductAttributes
{
    use Dispatchable;

    protected $data;
    protected $product;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle(ProductRepository $product_repo, $fields): ?ProductAttribute
    {
        $range_from = $fields['range_from'];
        $range_to = $fields['range_to'];
        $payable_months = $fields['payable_months'];
        $number_of_years = $fields['number_of_years'];
        $minimum_downpayment = $fields['minimum_downpayment'];
        $interest_rate = $fields['interest_rate'];
        $productAttributes = new ProductAttribute(
            compact(
                'range_from',
                'range_to',
                'payable_months',
                'number_of_years',
                'minimum_downpayment',
                'interest_rate'
            )
        );
        $product_repo->removeProductAttribute($productAttributes, $this->product);
        $productAttribute = $product_repo->saveProductAttributes($productAttributes, $this->product);

        return $productAttribute;
    }

    private function saveProductCombinations(Request $request, Product $product): bool
    {

        foreach($fields as $field) {
            $fields = $request->only(
                'productAttributeQuantity',
                'productAttributePrice',
                'sale_price',
                'default'
            );

            if ($errors = $this->validateFields($fields)) {
                return redirect()->route('admin.products.edit', [$product->id, 'combination' => 1])
                    ->withErrors($errors);
            }

            $quantity = $field['quantity'];
            $price = $field['price'];

            $sale_price = null;
            if (isset($field['sale_price'])) {
                $sale_price = $field['sale_price'];
            }

            $attributeValues = $field['attribute_value'];
            $productRepo = new ProductRepository($product);

            $hasDefault = $productRepo->listProductAttributes()->where('default', 1)->count();


            if ($field['default'] == 1 && $hasDefault > 0) {
                $default = 0;
            } else {
                $default = 1;
            }

            $productAttribute = $productRepo->saveProductAttributes(
                new ProductAttribute(compact('quantity', 'price', 'sale_price', 'default'))
            );

            // save the combinations
            return collect($attributeValues)->each(function ($attributeValueId) use ($productRepo, $productAttribute) {
                $attribute = $this->attributeValueRepository->find($attributeValueId);
                return $productRepo->saveCombination($productAttribute, $attribute);
            })->count();
        }
    }

    /**
     * @param array $data
     *
     * @return
     */
    private function validateFields(array $data)
    {
        $validator = Validator::make($data, [
            'productAttributeQuantity' => 'required'
        ]);

        if ($validator->fails()) {
            return $validator;
        }
    }
}
