<?php

namespace App\Repositories\Interfaces;

use App\Models\Department;
use App\Models\User;
use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     *
     * @param type $columns
     * @param string $orderBy
     * @param string $sortBy
     */
    public function listUsers($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection;

    /**
     *
     */
    public function deleteUser(): bool;

    /**
     *
     * @param array $data
     */
    //public function updateUser(array $data) : bool;

    /**
     *
     * @param int $id
     */
    public function findUserById(int $id): User;

    /**
     *
     * @param array $data
     */
    //public function createUser(array $data) : User;

    public function save(array $data, User $user): ?User;

    /**
     *
     * @param string $username
     */
    public function findUserByUsername(string $username): ?User;

    /**
     *
     * @param Department $objDepartment
     */
    public function getUsersForDepartment(Department $objDepartment): Collection;
}
