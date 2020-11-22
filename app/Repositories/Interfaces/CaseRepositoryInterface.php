<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use App\Models\Cases;
use App\Models\Task;
use App\Models\User;
use App\Requests\SearchRequest;
use Illuminate\Support\Collection;

interface CaseRepositoryInterface
{
    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     */
    public function getAll(SearchRequest $search_request, Account $account);

    /**
     * @param int $id
     * @return Cases
     */
    public function findCaseById(int $id): Cases;

    /**
     * @param array $data
     * @param Cases $case
     * @return Cases|null
     */
    public function save(array $data, Cases $case): ?Cases;

    /**
     * @param array $data
     * @param Cases $case
     * @return Cases|null
     */
    public function createCase(array $data, Cases $case): ?Cases;

    /**
     * @param array $data
     * @param Cases $case
     * @param User $user
     * @return Cases|null
     */
    public function updateCase(array $data, Cases $case, User $user): ?Cases;

}