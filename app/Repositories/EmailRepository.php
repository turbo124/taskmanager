<?php

namespace App\Repositories;

use App\Email;
use App\Event;
use App\Notification;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use Exception;
use Illuminate\Support\Collection;
use App\Repositories\UserRepository;
use App\User;
use App\Task;

class EmailRepository extends BaseRepository
{

    /**
     * EmailRepository constructor.
     * @param Email $email
     */
    public function __construct(Email $email)
    {
        parent::__construct($email);
        $this->model = $email;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     *
     * @return User
     * @throws Exception
     */
    public function findEmailById(int $id): Email
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listEmails($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }


    /**
     * @param array $data
     * @param Email $event
     */
    public function save(array $data, Email $email): ?Email
    {
        $email->fill($data);
        $email->save();


        return $email->fresh();

    }
}
