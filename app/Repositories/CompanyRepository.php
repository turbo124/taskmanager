<?php

namespace App\Repositories;

use App\Events\Company\CompanyWasCreated;
use App\Events\Company\CompanyWasUpdated;
use App\Models\Company;
use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{

    private $contact_repo;

    /**
     * CompanyRepository constructor.
     *
     * @param Company $company
     */
    public function __construct(Company $company, CompanyContactRepository $contact_repo)
    {
        parent::__construct($company);
        $this->model = $company;
        $this->contact_repo = $contact_repo;
    }

    /**
     * @param int $id
     * @return Company
     */
    public function findCompanyById(int $id): Company
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleteCompany(): bool
    {
        return $this->delete();
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listBrands($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

    /**
     * @param Product $product
     */
    public function saveProduct(Product $product)
    {
        $this->model->products()->save($product);
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $data
     * @param Company $company
     * @return Company|null
     */
    public function save(array $data, Company $company): ?Company
    {
        $is_add = empty($company->id);

        $company->fill($data);
        $company->setNumber();
        $company->save();

        if($is_add) {
            event(new CompanyWasCreated($company));
        } else {
            event(new CompanyWasUpdated($company));
        }

        return $company->fresh();
    }
}
