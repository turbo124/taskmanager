<?php

namespace App\Services\Product;

use App\Product;
use App\ProductImage;
use App\Repositories\ProductRepository;
use App\Services\ServiceBase;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as Support;

class ProductService extends ServiceBase
{
    use UploadableTrait;

    private $product;

    /**
     * ProductService constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct($product);
        $this->product = $product;
    }

    public function createProduct(ProductRepository $product_repo, array $data): Product
    {
        return (new CreateProduct($product_repo, $data, $this->product))->execute();
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function saveCoverImage(UploadedFile $file): string
    {
        return $file->store('products', ['disk' => 'public']);
    }

    /**
     * @param Support $collection
     * @param Product $product
     * @return bool
     */
    public function saveProductImages(Support $collection, Product $product): bool
    {
        $collection->each(
            function (UploadedFile $file) use ($product) {
                $filename = $this->storeFile($file);
                $productImage = new ProductImage(
                    [
                        'product_id' => $product->id,
                        'src'        => $filename
                    ]
                );
                $product->images()->save($productImage);
            }
        );

        return true;
    }
}
