<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\User;
use App\Models\Task;
use App\Models\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'beginDate',
        'endDate',
        'customer_id',
        'location',
        'event_type',
        'description',
        'created_by'
    ];

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Models\User::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Models\Task::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Models\User::class, 'created_by');
    }
}
