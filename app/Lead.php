<?php
/**
 * Created by PhpStorm.
 * User: michael.hampton
 * Date: 27/02/2020
 * Time: 19:50
 */

namespace App;


use App\Services\Lead\LeadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Notifications\Notifiable;

class Lead extends Model
{
    use SoftDeletes;
    use PresentableTrait;
    use Notifiable;

    protected $presenter = 'App\Presenters\LeadPresenter';

    const NEW_LEAD = 98;
    const IN_PROGRESS = 99;
    const STATUS_COMPLETED = 100;
    const UNQUALIFIED = 100;

    protected $fillable = [
        'number',
        'account_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address_1',
        'address_2',
        'zip',
        'city',
        'job_title',
        'company_name',
        'description',
        'title',
        'valued_at',
        'source_type',
        'assigned_user_id',
        'website',
        'industry_id',
        'private_notes',
        'public_notes',
        'status_id'
    ];

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity(null, $this);
            return true;
        }

        return true;
    }

    public function service(): LeadService
    {
        return new LeadService($this);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'documentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function preferredLocale()
    {
        return 'en';
    }
}
