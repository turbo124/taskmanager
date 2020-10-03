<?php

namespace App\Models;

use App\Models;
use App\Traits\Inviteable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Invitation.
 */
class Invitation extends Model
{

    use SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'key',
        'contact_id'
    ];

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function contact()
    {
        return $this->belongsTo(Models\CustomerContact::class, 'contact_id', 'id')->withTrashed();
    }

    public function inviteable()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    public function getLink()
    {
        return $this->account->subdomain . 'portal/invoices/' . $this->key;
    }

    public function markViewed()
    {
        $this->viewed_date = Carbon::now();
        $this->save();
    }

}
