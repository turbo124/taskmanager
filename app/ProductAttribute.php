<?php

namespace App;

use App\Product;
use App\Traits\ManageStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use ManageStock;

    protected $fillable = [
        'quantity',
        'price',
        'cost',
        'is_default'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributesValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }
}
