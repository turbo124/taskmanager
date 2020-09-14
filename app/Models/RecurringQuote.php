<?php

namespace App\Models;

use App\Services\RecurringQuote\RecurringQuoteService;
use App\Traits\Balancer;
use App\Traits\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laracasts\Presenter\PresentableTrait;

/**
 * Class for Recurring Invoices.
 */
class RecurringQuote extends Model
{
    use SoftDeletes;
    use PresentableTrait;
    use Balancer;
    use Money;

    protected $presenter = 'App\Presenters\QuotePresenter';

    /**
     * Invoice Statuses
     */
    const STATUS_DRAFT = 2;
    const STATUS_ACTIVE = 3;
    const STATUS_PENDING = -1;
    const STATUS_COMPLETED = -2;
    const STATUS_CANCELLED = -3;

    protected $fillable = [
        'account_id',
        'status_id',
        'customer_id',
        'quote_number',
        'discount',
        'total',
        'sub_total',
        'tax_total',
        'discount_total',
        'partial_due_date',
        'is_amount_discount',
        'po_number',
        'date',
        'due_date',
        'grace_period',
        'valid_until',
        'line_items',
        'settings',
        'footer',
        'public_notes',
        'private_notes',
        'terms',
        'frequency',
        'start_date',
        'end_date',
        'due_date',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'tax_rate_name',
        'tax_rate',
        'next_send_date'
    ];

    protected $casts = [
        'next_send_date' => 'datetime',
        'settings'       => 'object',
        'line_items'     => 'object',
        'updated_at'     => 'timestamp',
        'deleted_at'     => 'timestamp',
    ];

    protected $dates = [
        'next_send_date',
        'last_sent_date',
        'start_date',
        'end_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id')->withTrashed();
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class, "recurring_quote_id", "id")->withTrashed();
    }

    public function invitations()
    {
        $this->morphMany(QuoteInvitation::class);
    }

    public function service(): RecurringQuoteService
    {
        return new RecurringQuoteService($this);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function audits()
    {
        return $this->hasManyThrough(Audit::class, Notification::class, 'entity_id')->where(
            'entity_class',
            '=',
            get_class($this)
        )->orderBy('created_at', 'desc');
    }

    public function setNumber()
    {
        if (!empty($this->number)) {
            return true;
        }

        $this->number = (new NumberGenerator)->getNextNumberForEntity($this, $this->customer);
        return true;
    }

    public function setDueDate()
    {
        $this->due_date = !empty($this->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $this->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : null;
    }

    public function getPdfFilename()
    {
        return 'storage/' . $this->account->id . '/' . $this->customer->id . '/recurring_quotes/' . $this->number . '.pdf';
    }

    public function getDesignId()
    {
        return !empty($this->design_id) ? $this->design_id : $this->customer->getSetting('quote_design_id');
    }
}
