<?php

namespace App;

use App\ClientContact;
use App\Invoice;
use App\Traits\Inviteable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class CreditInvitation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'customer_id',
    ];

    /**
     * @return mixed
     */
    public function credit()
    {
        return $this->belongsTo(Credit::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getName()
    {
        return $this->key;
    }

    public function markViewed()
    {
        $this->viewed_date = Carbon::now();
        $this->save();
    }

    public function getLink()
    {
        return $this->account->subdomain . 'portal/credit/' . $this->key;
    }

    /**
     * @return mixed
     */
    public function contact()
    {
        return $this->belongsTo(ClientContact::class, 'client_contact_id', 'id')->withTrashed();
    }
}
