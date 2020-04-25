<?php

namespace App\Repositories;

use App\CompanyToken;
use App\Quote;
use App\Repositories\Base\BaseRepository;
use App\Subscription;

class SubscriptionRepository extends BaseRepository
{
    /**
     * SubscriptionRepository constructor.
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        parent::__construct($subscription);
        $this->model = $subscription;
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
     * @return Subscription
     */
    public function findSubscriptionById(int $id): Subscription
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $data
     * @param Subscription $subscription
     * @return Subscription|null
     */
    public function save(array $data, Subscription $subscription): ?Subscription
    {
        $subscription->fill($data);

        $subscription->save();

        return $subscription;
    }
}
