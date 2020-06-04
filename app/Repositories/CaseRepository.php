<?php


namespace App\Repositories;


use App\Cases;
use App\Repositories\Base\BaseRepository;

class CaseRepository extends BaseRepository
{
    /**
     * CaseRepository constructor.
     * @param Cases $case
     */
    public function __construct(Cases $case)
    {
        parent::__construct($case);
        $this->model = $case;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     * @return Cases
     */
    public function findCaseById(int $id): Cases
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $data
     * @param Cases $case
     */
    public function save(array $data, Cases $case)
    {
        $case->fill($data);
        $case->setNumber();
        $case->save();
        return $case;
    }
}
