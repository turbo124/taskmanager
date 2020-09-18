<?php

namespace App\Models;

use App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringQuoteInvitation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'contact_id',
    ];

    /**
     * @return mixed
     */
    public function recurring_quote()
    {
        return $this->belongsTo(RecurringQuote::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(Models\User::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function contact()
    {
        return $this->belongsTo(CustomerContact::class, 'contact_id', 'id')->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getLink()
    {
        return $this->account->subdomain . 'portal/recurringQuote/' . $this->key;
    }

    public function markViewed()
    {
        $this->viewed_date = Carbon::now();
        $this->save();
    }
}
