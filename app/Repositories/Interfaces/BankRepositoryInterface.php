<?php

namespace App\Repositories\Interfaces;

use App\Models\Bank;
use App\Models\Product;
use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface BankRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $data
     * @param Bank $bank
     * @return Bank
     */
    public function save(array $data, Bank $bank): Bank;

    /**
     * @param int $id
     * @return BankAccount
     */
    public function findBankById(int $id): Bank;
}
