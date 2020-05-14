<?php

namespace App\Repositories;

use App\Timer;
use App\Task;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

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
    public function save(Task $task, Timer $timer, array $data): ?Timer
    {
        $start_time = date('H:i:s', strtotime($data['start_time']));
        $end_time = date('H:i:s', strtotime($data['end_time']));

        $timer->started_at = date('Y-m-d H:i:s', strtotime($data['date'] . ' ' . $start_time));
        $timer->stopped_at = empty($data['end_time']) ? null : date('Y-m-d') . $end_time;
        $timer->task_id = $task->id;

        $timer->save();

        return $timer;
    }

    public function getTotalDuration(Task $task)
    {
        $timers = DB::table('timers')
                    ->select(\DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, started_at, stopped_at)/60, 2) as hours'))
                    ->where('task_id', '=', $task->id)
                    ->get();

        return array_sum(array_column($timers->toArray(), 'hours'));
    }

    public function isRunning(Task $task)
    {
        $timer = Timer::whereNull('stopped_at')->where('task_id', '=', $task->id)->first();

        return !empty($timer);
    }
}
