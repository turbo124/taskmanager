<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Archiveable;

class Brand extends Model
{
    use SoftDeletes;
    use Archiveable;

    protected $fillable = [
        'name',
        'status',
        'cover',
        'description'
    ];

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
