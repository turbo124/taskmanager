<?php

namespace App;

use App\Services\Quote\QuoteService;
use Illuminate\Database\Eloquent\Model;
use App\Task;
use App\InvoiceStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laracasts\Presenter\PresentableTrait;

class Quote extends Model
{
    use SoftDeletes;
    use PresentableTrait;

    protected $presenter = 'App\Presenters\QuotePresenter';

    protected $casts = [
        'line_items' => 'object',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
        'is_deleted' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'customer_id',
        'order_id',
        'total',
        'sub_total',
        'tax_total',
        'tax_rate',
        'tax_rate_name',
        'discount_total',
        'payment_type',
        'due_date',
        'status_id',
        'finance_type',
        'created_at',
        'start_date',
        'end_date',
        'frequency',
        'recurring_due_date',
        'public_notes',
        'private_notes',
        'terms',
        'footer',
        'partial',
        'partial_due_date',
        'date',
        'balance',
        'line_items',
        'company_id',
        'task_id',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'custom_surcharge1',
        'custom_surcharge2',
        'custom_surcharge3',
        'custom_surcharge4',
        'custom_surcharge_tax1',
        'custom_surcharge_tax2',
        'custom_surcharge_tax3',
        'custom_surcharge_tax4',
        'number',
        'invoice_type_id',
        'is_amount_discount',
        'po_number',
        'design_id'
    ];

    const STATUS_DRAFT = 1;
    const STATUS_SENT = 2;
    const STATUS_APPROVED = 4;
    const STATUS_EXPIRED = -1;

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    /**
     * @return BelongsTo
     */
    public function paymentType()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_type');
    }

    /**
     * @return mixed
     */
    public function invitations()
    {
        return $this->hasMany(QuoteInvitation::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function service(): QuoteService
    {
        return new QuoteService($this);
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'documentable');
    }
}
