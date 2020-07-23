<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use App\Models\Invoice;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Models\Product;
use App\Requests\SearchRequest;
use Illuminate\Support\Collection as Support;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Task;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * @param SearchRequest $search_request
     * @param \App\Models\Account $account
     * @return mixed
     */
    public function getAll(SearchRequest $search_request, Account $account);

    /**
     * @param $data
     * @param \App\Models\Product $product
     * @return \App\Models\Product|null
     */
    public function save(array $data, Product $product): ?Product;

    /**
     * @param int $id
     * @return \App\Models\Product
     */
    public function findProductById(int $id): Product;

    /**
     *
     */
    public function deleteProduct(): bool;

    /**
     * @param string $slug
     * @return \App\Models\Product
     */
    public function findProductBySlug(string $slug): Product;

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
     * @param \App\Models\ProductAttribute $productAttribute
     * @param \App\Models\Product $product
     * @return \App\Models\ProductAttribute
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
