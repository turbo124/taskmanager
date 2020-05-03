<?php

namespace App\Repositories;

use App\Timer;
use App\Repositories\Base\BaseRepository;

class TimerRepository extends BaseRepository
{
    /**
     * TimerRepository constructor.
     * @param Timer $timer
     */
    public function __construct(Timer $timer)
    {
        parent::__construct($timer);
        $this->model = $timer;
    }

    /**
     * Gets the class name.
     *
     * @return     string The class name.
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     * @return Timer
     */
    public function findTimerById(int $id): Timer
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $data
     * @param Timer $timer
     * @return Timer|null
     */
    public function save(array $data, Timer $timer): ?Timer
    {
        $timer->fill($data);

        $timer->save();

        return $timer;
    }
}
