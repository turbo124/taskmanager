<?php

namespace App\Repositories;

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
     *
     * @return Brand
     */
    public function findBrandById(int $id): Company
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleteBrand(): bool
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
     * @return Collection
     */
    public function listProducts(): Collection
    {
        return $this->model->products()->get();
    }

    /**
     * @param Product $product
     */
    public function saveProduct(Product $product)
    {
        $this->model->products()->save($product);
    }

    /**
     * Dissociate the products
     */
    public function dissociateProducts()
    {
        $this->model->products()->each(
            function (Product $product) {
                $product->company_id = null;
                $product->save();
            }
        );
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sync the users
     *
     * @param array $params
     */
    public function syncUsers($user_id, array $params)
    {
        $this->model->users()->attach($user_id, $params);
    }

    /**
     * Saves the client and its contacts
     *
     * @param array $data The data
     * @param Company $client The Company
     *
     * @return     Client|Company|null  Company Object
     */
    public function save(array $data, Company $company): ?Company
    {
        $company->fill($data);
        $company->setNumber();
        $company->save();

        return $company;
    }


    /**
     * Store vendors in bulk.
     *
     * @param array $vendor
     * @return vendor|null
     */
    public function create($company): ?Company
    {
        return $this->save($company, CompanyFactory::create(auth()->user()->company()->id, auth()->user()->id));
    }
}
