<?php

namespace App\Services\Transaction;

use App\Transaction;
use App\Factory\TransactionFactory;
use App\Services\ServiceBase;

class TransactionService extends ServiceBase
{

    private $entity;

    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    public function createTransaction($amount, $notes = '')
    {
        $transaction = $this->transaction();

        $balance = 0;

        if ($transaction) {
            $balance = $transaction->balance;
        }

        $transaction = new Transaction;
        $transaction->setAccount($this->entity->account);
        $transaction->setUser($this->entity->user);
        $transaction->setCustomer($this->entity->customer);
        $transaction->setUpdatedBalance($balance + $amount);
        $transaction->setAmount($amount);
        $transaction->setNotes($notes);
        $transaction->createTransaction();

        $this->entity->transactions()->save($transaction);

        return $transaction;
    }

    private function transaction(): ?Transaction
    {
        return Transaction::whereCustomerId($this->entity->customer_id)
                          ->whereAccountId($this->entity->account_id)
                          ->orderBy('id', 'DESC')
                          ->first();
    }
}
