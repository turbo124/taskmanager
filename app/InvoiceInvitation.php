<?php

namespace App;

use App\ClientContact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Traits\Inviteable;

/**
 * Class Invitation.
 */
class InvoiceInvitation extends Model
{

    use SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'key',
        'client_contact_id'
    ];

    /**
     * @return mixed
     */
    public function invoice()
    {
        return $this->belongsTo('App\Invoice')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function contact()
    {
        return $this->belongsTo(ClientContact::class, 'client_contact_id', 'id')->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function getLink()
    {
        return $this->account->subdomain . 'portal/invoice/' . $this->key;
    }

    public function markViewed()
    {
        $this->viewed_date = Carbon::now();
        $this->save();
    }

}
