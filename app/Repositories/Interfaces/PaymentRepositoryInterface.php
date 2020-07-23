<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Models\Payment;
use App\Requests\SearchRequest;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * @param int $id
     * @return \App\Models\Payment
     */
    public function findPaymentById(int $id): Payment;

    /**
     * @param SearchRequest $search_request
     * @param \App\Models\Account $account
     * @return mixed
     */
    public function getAll(SearchRequest $search_request, Account $account);

    public function deletePayment();

    /**
     * @param array $request
     * @param \App\Models\Payment $payment
     * @return \App\Models\Payment|null
     */
    public function save(array $request, Payment $payment): ?Payment;
}
