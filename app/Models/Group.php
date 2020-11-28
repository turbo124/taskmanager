<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Archiveable;

class Group extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Archiveable;

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
     *
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $this->decodePrimaryKey($value))->firstOrFail();
    }
}
