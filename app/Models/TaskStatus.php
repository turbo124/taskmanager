<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskType;
use App\Traits\SearchableTrait;

class TaskStatus extends Model
{

    use SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'column_color',
        'task_type'
    ];

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'task_statuses.name' => 10
        ]
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class, 'task_type');
    }

    /**
     * @param $term
     *
     * @return mixed
     */
    public function searchTaskStatus($term)
    {
        return self::search($term);
    }

}
