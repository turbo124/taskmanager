<?php

namespace App\Models;

use App\Services\Deal\DealService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;

class Deal extends Model
{

    use SoftDeletes;
    use PresentableTrait;

    protected $fillable = [
        'name',
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

    protected $presenter = 'App\Presenters\DealPresenter';

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

    public function service(): DealService
    {
        return new DealService($this);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status');
    }

    public function getDesignId()
    {
        return !empty($this->design_id) ? $this->design_id : $this->customer->getSetting('deal_design_id');
    }

    public function getPdfFilename()
    {
        return 'storage/' . $this->account->id . '/' . $this->customer->id . '/deals/' . $this->number . '.pdf';
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this, $this->customer);
            return true;
        }

        return true;
    }

    public function getNumber()
    {
        return $this->number;
    }
}
