<?php

namespace App\Repositories\Interfaces;

use App\Account;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Category;
use App\Product;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{

    /**
     *
     * @param string $order
     * @param string $sort
     * @param type $except
     */
    public function listCategories(string $order = 'id', string $sort = 'desc', Account $account, $except = []): Collection;

    /**
     * @param array $params
     * @param Account $account
     * @return Category
     */
    public function createCategory(array $params, Account $account): Category;

    /**
     *
     * @param array $params
     */
    public function updateCategory(array $params): Category;

    /**
     *
     * @param int $id
     */
    public function findCategoryById(int $id): Category;

    /**
     *
     */
    public function deleteCategory(): bool;

    /**
     *
     * @param Product $product
     */
    public function associateProduct(Product $product);

    /**
     *
     */
    public function findProducts(): Collection;

    /**
     *
     * @param array $params
     */
    public function syncProducts(array $params);

    /**
     *
     */
    public function detachProducts();

    /**
     *
     * @param array $file
     * @param type $disk
     */
    public function deleteFile(array $file, $disk = null): bool;

    /**
     * @param string $slug
     * @param Account $account
     * @return Category
     */
    public function findCategoryBySlug(string $slug, Account $account): Category;

    /**
     *
     * @param string $string
     * @param string $string1
     */
    public function rootCategories(string $string, string $string1);
}
