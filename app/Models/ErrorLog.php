<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ErrorLog extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data',
        'error_type',
        'error_result',
        'entity',
        'entity_id',
        'account_id',
        'user_id',
        'customer_id',
       
    ];

    protected $casts = [
        'data' => 'object'
    ];

     /**
     * type
     */
    const PAYMENT_FAILURE = 'payment_failure';
    const EMAIL_FAILURE = 'email_failure';
   
    /**
     * result
     */
    const SUCCESS = 'success';
    const NEUTRAL = 'neutral';
    const FAILURE = 'failure';

    protected $table = 'error_log'
}
