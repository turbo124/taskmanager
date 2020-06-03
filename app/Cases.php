<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cases extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status_id',
        'priority_id',
        'category_id',
        'due_date',
        'private_notes',
        'subject',
        'number',
        'message',
        'user_id',
        'account_id',
        'customer_id'
    ];

    const STATUS_DRAFT = 1;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

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

        public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this->customer, $this);
            return true;
        }

        return true;
    }
}
