<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{

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

    /**
     * @param $quantity
     */
    public function reduceQuantityAvailiable($quantity)
    {
        $this->quantity -= $quantity;
        $this->save();
    }

    /**
     * @param int $quantity
     */
    public function reduceQuantityReserved(int $quantity)
    {
        $this->reserved_stock -= $quantity;
        $this->save();
    }

    /**
     * @param int $quantity
     */
    public function increaseQuantityReserved(int $quantity)
    {
        $this->reserved_stock += $quantity;
        $this->save();
    }
}
