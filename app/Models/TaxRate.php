<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRate extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'rate'
    ];
    protected $searchable = [
        'columns' => [
            'tax_rates.name' => 10
        ]
    ];
    //protected $table = 'tax_rates';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @param $term
     *
     * @return mixed
     */
    public function searchTaxRate($term)
    {
        return self::search($term);
    }

}
