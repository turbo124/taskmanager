<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Archiveable;

class Project extends Model
{

    use SearchableTrait;
    use SoftDeletes;
    use HasFactory;
    use Archiveable;

    protected $fillable = [
        'name',
        'description',
        'customer_id',
        'number',
        'account_id',
        'assigned_to',
        'user_id',
        'account_id',
        'private_notes',
        'public_notes',
        'due_date',
        'budgeted_hours',
        'task_rate'
    ];

    protected $casts = [
        'updated_at' => 'timestamp',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'projects.title' => 10,
        ]
    ];

    /**
     * @return BelongsToMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @param $term
     *
     * @return mixed
     */
    public function searchProject($term)
    {
        return self::search($term);
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this);
            return true;
        }

        return true;
    }
}
