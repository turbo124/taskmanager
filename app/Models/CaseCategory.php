<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseCategory extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsTo
     */
    public function cases()
    {
        return $this->belongsTo('App\Models\Cases');
    }
}
