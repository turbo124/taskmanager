<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'customer_id',
        'updated_balance',
        'amount',
        'notes',
        'account_id',
        'user_id'
    ];

    protected $casts = [
        'updated_at' => 'timestamp',
        'created_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function setUser(User $user)
    {
        $this->user_id = $user->id;
    }

    public function setAccount(Account $account)
    {
        $this->account_id = $account->id;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer_id = $customer->id;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function setUpdatedBalance($updated_balance)
    {
        $this->updated_balance = $updated_balance;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function createTransaction()
    {
        $this->create(
            [
                'user_id'         => $this->user_id,
                'account_id'      => $this->account_id,
                'customer_id'     => $this->customer_id,
                'updated_balance' => $this->balance,
                'amount'          => $this->adjustment,
                'notes'           => $this->notes
            ]
        );
    }
}
