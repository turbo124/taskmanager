<?php

namespace App\Jobs\Product;

use App\Repositories\ProductRepository;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductRepository $product_repo;

    private Product $product;

    private array $data;

    /**
     * Create a new job instance.
     *
     * @param  ProductRepository  $product_repo
     * @return void
     */
    public function __construct(ProductRepository $product_repo, array $data, Product $product)
    {
        $this->product_repo = $product_repo;
        $this->data = $data;
        $this->product;
    }
    
    public function handle()
    {
        $this->data['slug'] = Str::slug($this->data['name']);

        if (!empty($this->data['cover']) && $this->data['cover'] instanceof UploadedFile) {
            $this->data['cover'] = $this->product_repo->saveCoverImage($this->data['cover']);
        }

        $this->data['is_featured'] = !empty($this->data['is_featured']) && $this->data['is_featured'] === 'true' ? 1 : 0;

        $this->product_repo->save($this->data, $this->product);

        if (!empty($this->data['features'])) {
            $this->saveProductFeatures($this->product, $this->data['features']);
        }

        if (isset($this->data['image']) && !empty($this->data['image'])) {
            $this->product_repo->saveProductImages(collect($this->data['image']), $this->product);
        }

        if (isset($this->data['category']) && !empty($this->data['category'])) {
            $categories = !is_array($this->data['category']) ? explode(',', $this->data['category']) : $this->data['category'];
            $this->product_repo->syncCategories($categories, $this->product);
        } else {
            $this->detachCategories($this->product);
        }

        if(!empty($this->data['variations'])) {
            $this->saveVariations($this->data['variations']);
        }
    }

    private function saveVariations($fields): bool
    {
        $variations = json_decode($fields, true);

        if (empty($variations)) {
            return true;
        }

        $this->product->attributes()->forceDelete();

        foreach ($variations as $variation) {
            $hasDefault = $this->product_repo->listProductAttributes()->where('default', 1)->count();
            $variation['is_default'] = $variation['is_default'] == 1 && $hasDefault > 0 ? 0 : 1;

            $objProductAttribute = new ProductAttribute();
            $objProductAttribute->fill($variation);

            $productAttribute = $this->product_repo->saveProductAttributes(
                $objProductAttribute,
                $this->product
            );

            foreach ($variation['attribute_values'] as $value) {
                $attribute = (new AttributeValueRepository(new AttributeValue))->find($value);
                $this->product_repo->saveCombination($productAttribute, $attribute);
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
