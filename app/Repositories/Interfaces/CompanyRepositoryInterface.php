<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use App\Models\Company;
use App\Models\Product;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Requests\SearchRequest;
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
     * @param SearchRequest $search_request
     * @param Account $account
     * @return mixed
     */
    public function getAll(SearchRequest $search_request, Account $account);

    /**
     * @param array $data
     * @param Company $company
     * @return Company|null
     */
    public function save(array $data, Company $company): ?Company;
}
