<?php

namespace App\Repositories;

use App\Design;
use App\Repositories\Base\BaseRepository;
use App\Customer;

/**
 * Class DesignRepository
 * @package App\Repositories
 */
class DesignRepository extends BaseRepository
{

    /**
     * CustomerRepository constructor.
     * @param Customer $customer
     */
    public function __construct(Design $design)
    {
        parent::__construct($design);
        $this->model = $design;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     * @return Lead
     */
    public function findDesignById(int $id): Design
    {
        return $this->findOneOrFail($id);
    }

}
