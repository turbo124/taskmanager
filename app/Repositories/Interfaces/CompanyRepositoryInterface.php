<?php

namespace App\Repositories\Interfaces;

use App\Models\Company;
use App\Models\Product;
use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface CompanyRepositoryInterface extends BaseRepositoryInterface
{

    /**
     *
     * @param int $id
     */
    public function findCompanyById(int $id): Company;

    /**
     *
     */
    public function deleteCompany(): bool;

    /**
     *
     * @param type $columns
     * @param string $orderBy
     * @param string $sortBy
     */
    public function listBrands($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection;

    /**
     *
     * @param Product $product
     */
    public function saveProduct(Product $product);

    public function save(array $data, Company $company): ?Company;
}
