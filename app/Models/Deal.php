<?php

namespace App\Models;

use App\Libraries\Utils;
use App\Services\Task\TaskService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'assigned_to',
        'due_date',
        'project_id',
        'task_status',
        'created_by',
        'task_type',
        'customer_id',
        'rating',
        'valued_at',
        'parent_id',
        'source_type',
        'time_log',
        'is_running',
        'account_id',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'public_notes',
        'private_notes'
    ];

    protected $casts = [
        'updated_at' => 'timestamp',
    ];

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function service(): TaskService
    {
        return new TaskService($this);
    }
}
