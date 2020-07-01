<?php

namespace App;

use App\Services\RecurringQuote\RecurringQuoteService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Account;

/**
 * Class for Recurring Invoices.
 */
class RecurringQuote extends Model
{
    use SoftDeletes;

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
        'tax_rate'
    ];
    protected $casts = [
        'settings'   => 'object',
        'line_items' => 'object',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
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
        return $this->belongsTo(User::class, 'assigned_user_id', 'id')->withTrashed();
    }

    public function service(): RecurringQuoteService
    {
        return new RecurringQuoteService($this);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function setNumber()
    {
        if (!empty($this->number)) {
            return true;
        }

        $this->number = (new NumberGenerator)->getNextNumberForEntity($this, $this->customer);
        return true;
    }
}
