<?php


namespace App\Repositories;


use App\Promocode;
use App\Repositories\Base\BaseRepository;

/**
 * Class PromocodeRepository
 * @package App\Repositories
 */
class PromocodeRepository extends BaseRepository
{
    /**
     * PromocodeRepository constructor.
     * @param Promocode $promocode
     */
    public function __construct(Promocode $promocode)
    {
        parent::__construct($promocode);
        $this->model = $promocode;
    }

    public function getModel()
    {
        return $this->model;
    }

}