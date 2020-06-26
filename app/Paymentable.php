<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Payment;

class Paymentable extends Pivot
{
    protected $table = 'paymentables';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'timestamp',
        'created_at' => 'timestamp',
        'deleted_at' => 'timestamp',
        'settings'   => 'object',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class)->withTrashed();
    }
}
