<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductFeature
 * @package App
 */
class ProductFeature extends Model
{
    protected $fillable = [
        'description',
        'product_id'
    ];
}