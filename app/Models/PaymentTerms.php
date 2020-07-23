<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/**
 * Class PaymentTerm.
 */
class PaymentTerms extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'number_of_days'];
}
