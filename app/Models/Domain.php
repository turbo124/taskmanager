<?php

namespace App\Models;

use App\Models\Account;
use App\Models\AccountUser;
use App\Models\Customer;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;

class Domain extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [

    ];
    /**
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'promo_expires',
        'discount_expires',
    ];

    /**
     * @return HasMany
     */
    public function default_company()
    {
        return $this->hasOne(Account::class, 'id', 'default_account_id');
    }

    /**
     * @return BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class)->withTrashed();
    }

    public function companies()
    {
        return $this->hasMany(Account::class);
    }

    public function company_users()
    {
        return $this->hasMany(AccountUser::class);
    }

    public function users()
    {
        return $this->hasMany(User::class)->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
