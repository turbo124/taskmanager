<?php

namespace App\Repositories\Interfaces;

use App\Account;
use App\Invoice;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Product;
use App\Requests\SearchRequest;
use Illuminate\Support\Collection as Support;
use Illuminate\Database\Eloquent\Collection;
use App\Task;
use App\Brand;
use App\Category;
use App\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return mixed
     */
    public function getAll(SearchRequest $search_request, Account $account);

    /**
     * @param $data
     * @param Product $product
     * @return Product|null
     */
    public function save($data, Product $product): ?Product;

    /**
     * @param int $id
     * @return Product
     */
    public function findProductById(int $id): Product;

    /**
     *
     */
    public function deleteProduct(): bool;

    /**
     *
     * @param array $slug
     */
    public function findProductBySlug(array $slug): Product;

    /**
     *
     */
    public function getCategories(): Collection;

    /**
     *
     */
    public function findBrand();

    /**
     *
     * @param \App\Repositories\Interfaces\Category $objCategory
     */
    public function filterProductsByCategory(Category $objCategory): Support;

    /**
     * @param ProductAttribute $productAttribute
     * @param Product $product
     * @return ProductAttribute
     */
    public function saveProductAttributes(ProductAttribute $productAttribute, Product $product): ProductAttribute;

    /**
     *
     */
    public function listProductAttributes(): Collection;

    /**
     *
     * @param ProductAttribute $productAttribute
     */
    public function removeProductAttribute(ProductAttribute $productAttribute, Product $product): ?bool;

    /**
     *
     * @param Category $category
     * @param type $value
     */
    public function getProductsByDealValueAndCategory(Category $category, Request $request);
}
