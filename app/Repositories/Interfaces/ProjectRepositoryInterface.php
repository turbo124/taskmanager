<?php

namespace App\Repositories\Interfaces;

use App\Models\Project;
use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    public function findProjectById(int $id): Project;

    public function save($data, Project $invoice): ?Project;

    public function deleteProject(): bool;

    public function listProjects($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection;
}
