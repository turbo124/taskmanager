<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/**
 * Class PaymentTerm.
 */
class PaymentTerm extends BaseModel
{
    use SoftDeletes;
}
