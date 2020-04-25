<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;
use App\Task;
use App\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'body',
        'design',
        'recipient',
        'recipient_email',
        'sent_at',
        'entity',
        'entity_id',
        'account_id',
        'user_id',
        'direction'
    ];

    protected $casts = [
        'design' => 'object'
    ];
}
