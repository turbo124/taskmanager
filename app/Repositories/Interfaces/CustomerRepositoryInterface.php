<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Account;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Customer;
use App\Requests\SearchRequest;
use Illuminate\Support\Collection as Support;

/**
 *
 * @author michael.hampton
 */
interface CustomerRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return mixed
     */
    public function getAll(SearchRequest $search_request, Account $account);

    /**
     * @param int $id
     * @return Customer
     */
    public function findCustomerById(int $id): Customer;

    /**
     *
     */
    public function deleteCustomer(): bool;

    /**
     * @param array $data
     * @param Customer $customer
     * @return Customer|null
     */
    public function save(array $data, Customer $customer): ?Customer;
}
