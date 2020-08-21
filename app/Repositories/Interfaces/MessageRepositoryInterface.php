<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use App\Models\Message;
use App\Models\User;

/**
 *
 * @author michael.hampton
 */
interface MessageRepositoryInterface
{

    /**
     *
     * @param array $data
     */
    public function createMessage(array $data): Message;

    public function deleteMessage(): bool;

    /**
     *
     * @param \App\Repositories\Interfaces\Customer $customer
     * @param \App\Repositories\Interfaces\User $user
     * @param type $blLastOnly
     */
    public function getMessagesForCustomer(Customer $customer, User $user, $blLastOnly = false);
}
