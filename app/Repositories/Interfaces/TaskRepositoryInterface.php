<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Requests\SearchRequest;
use Illuminate\Support\Collection as Support;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param int $id
     * @return Task
     */
    public function findTaskById(int $id): Task;

    /**
     * @param $data
     * @param Task $task
     * @return Task|null
     */
    public function save($data, Task $task): ?Task;

    /**
     *
     */
    public function deleteTask(): bool;

    public function getAll(SearchRequest $search_request, Account $account);

    /**
     *
     * @param int $task_type
     * @param type $limit
     */
    public function getDeals($limit = null, User $objUser = null): Support;

    /**
     *
     * @param Project $objProject
     */
    public function getTasksForProject(Project $objProject, User $objUser = null): Support;

    /**
     *
     */
    public function getTasksWithProducts(): Support;


    public function getSubtasks(Task $objTask): Support;
}
