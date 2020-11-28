<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Archiveable;

class CaseCategory extends Model
{

    use SoftDeletes;
    use Archiveable;

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
