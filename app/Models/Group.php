<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    protected $casts = [
        'settings'   => 'object',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];
    protected $fillable = [
        'name',
        'settings'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'id', 'group_settings_id');
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @return Model|null
     */
    public function resolveRouteBinding($value)
    {
        return $this->where('id', $this->decodePrimaryKey($value))->firstOrFail();
    }
}
