<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Archiveable;

/**
 * Class ProductFeature
 * @package App
 */
class ProductFeature extends Model
{
    use Archiveable;

    protected $fillable = [
        'description',
        'product_id'
    ];
}
