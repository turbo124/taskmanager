<?php

namespace App;

use App\Services\RecurringInvoice\RecurringInvoiceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Account;

/**
 * Class for Recurring Invoices.
 */
class RecurringInvoice extends Model
{
    use SoftDeletes;

    /**
     * Invoice Statuses
     */
    const STATUS_DRAFT = 2;
    const STATUS_ACTIVE = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_PENDING = -1;
    const STATUS_COMPLETED = -2;


    protected $fillable = [
        'status_id',
        'account_id',
        'customer_id',
        'number',
        'total',
        'sub_total',
        'tax_total',
        'discount_total',
        'partial_due_date',
        'is_amount_discount',
        'po_number',
        'date',
        'due_date',
        'line_items',
        'footer',
        'public_notes',
        'private_notes',
        'terms',
        'total',
        'partial',
        'frequency_id',
        'start_date',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'tax_rate_name',
        'tax_rate',
        'settings',
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class, "id", "recurring_id")->withTrashed();
    }

    public function invitations()
    {
        $this->morphMany(InvoiceInvitation::class);
    }

    public function service(): RecurringInvoiceService
    {
        return new RecurringInvoiceService($this);
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

        $this->number = (new NumberGenerator)->getNextNumberForEntity($this->customer, $this);
        return true;
    }
}
