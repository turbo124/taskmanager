<?php

namespace App\Models;

use App\Traits\ManageStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Archiveable;

class ProductAttribute extends Model
{
    use ManageStock;
    use HasFactory;
    use Archiveable;

    protected $fillable = [
        'quantity',
        'price',
        'cost',
        'is_default'
    ];

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsToMany
     */
    public function attributesValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }
}
