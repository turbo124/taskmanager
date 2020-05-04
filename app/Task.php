<?php

namespace App;

use App\Services\Task\TaskService;
use Illuminate\Database\Eloquent\Model;
use App\Project;
use App\Product;
use App\Timer;
use App\User;
use App\TaskStatus;
use App\Customer;
use App\Comment;
use App\Libraries\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    use SoftDeletes;

    const TASK_TYPE_DEAL = 3;

    protected $fillable = [
        'title',
        'content',
        'is_completed',
        'due_date',
        'start_date',
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

    public function projects()
    {
        return $this->belongsTo(Project::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
         * Get associated timers.
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function timers()
        {
            return $this->hasMany(Timer::class);
        }

    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->belongsToMany(Comment::class);
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'documentable');
    }

    public function service(): TaskService
    {
        return new TaskService($this);
    }
}
