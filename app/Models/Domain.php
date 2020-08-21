<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'customer_id',
        'default_account_id'
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
