<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyLedger extends Model
{
    protected $fillable = [
        'customer_id',
        'balance',
        'adjustment',
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

    public function company_ledgerable()
    {
        return $this->morphTo();
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

    public function setBalance ($balance)
    {
        $this->balance = $balance;
    }

    public function setAdjustment($adjustment)
    {
        $this->adjustment = $adjustment;
    }

    public function createLedger()
    {
        $this->create(
            [
                'user_id' => $this->user_id,
                'account_id' => $this->account_id,
                'customer_id' => $this->customer_id,
                'balance' => $this->balance,
                'adjustment' => $this->adjustment,
                'notes' => $this->notes
            ]
        );
    }
}
