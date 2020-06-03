<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cases extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status_id',
        'subject',
        'message',
        'user_id',
        'account_id',
        'customer_id'
    ];

    const STATUS_DRAFT = 1;

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer_id = $customer->id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user_id = $user->id;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account_id = $account->id;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status_id = $status;
    }
}