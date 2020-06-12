<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;
use App\Product;
use App\AttributeValue;
use Illuminate\Support\Collection as Support;
use Illuminate\Database\Eloquent\Collection;
use App\Category;
use App\ProductImage;
use App\ProductAttribute;
use Illuminate\Http\UploadedFile;
use App\Traits\UploadableTrait;
use Illuminate\Support\Str;

class CreateProduct implements ShouldQueue
{

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
    
    public function execute()
    {
        $this->data['slug'] = Str::slug($this->data['name']);

        if (!empty($this->data['cover']) && $this->data['cover'] instanceof UploadedFile) {
            $this->data['cover'] = $this->saveCoverImage($this->data['cover']);
        }

        $this->data['is_featured'] = !empty($this->data['is_featured']) && $this->data['is_featured'] === 'true' ? 1 : 0;

        $this->product_repo->save($this->data, $this->product);

        if (!empty($this->data['features'])) {
            $this->saveProductFeatures($this->product, $this->data['features']);
        }

        if (isset($this->data['image']) && !empty($this->data['image'])) {
            $this->saveProductImages(collect($this->data['image']), $this->product);
        }

        $this->saveCategories();

        if(!empty($this->data['variations'])) {
            $this->saveVariations($this->data['variations']);
        }

        return $this->product;
    }

    private function saveCategories()
    {
        if (isset($this->data['category']) && !empty($this->data['category'])) {
            $categories = !is_array($this->data['category']) ? explode(',', $this->data['category']) : $this->data['category'];
            $this->product_repo->syncCategories($categories, $this->product);
            return true;
        }
            
        $this->detachCategories($this->product);
        
        return true;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    private function saveCoverImage(UploadedFile $file): string
    {
        return $file->store('products', ['disk' => 'public']);
    }

    /**
     * @param Support $collection
     * @param Product $product
     * @return bool
     */
    private function saveProductImages(Support $collection, Product $product): bool
    {
        $collection->each(
            function (UploadedFile $file) use ($product) {
                $filename = $this->storeFile($file);
                $productImage = new ProductImage(
                    [
                        'product_id' => $this->model->id,
                        'src'        => $filename
                    ]
                );
                $product->images()->save($productImage);
            }
        );

        return true;
    }

    /**
     * @param Product $product
     * @param $fields
     * @return bool
     */
    private function saveProductFeatures(Product $product, $fields): bool
    {
        $features = json_decode($fields, true);

        if (empty($features)) {
            return true;
        }

        $product->features()->forceDelete();

        foreach ($features as $feature) {
            $product->features()->create($feature);
        }

        return true;
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
                $this->saveCombination($productAttribute, $attribute);
            }
        }

        return true;
    }

     /**
     * @param ProductAttribute $productAttribute
     * @param AttributeValue ...$attributeValues
     *
     * @return Collection
     */
    private function saveCombination(
        ProductAttribute $productAttribute,
        AttributeValue ...$attributeValues
    ): \Illuminate\Support\Collection {
        return collect($attributeValues)->each(
            function (AttributeValue $value) use ($productAttribute) {
                return $productAttribute->attributesValues()->save($value);
            }
        );
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
