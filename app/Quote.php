<?php

namespace App;

use App\Services\Quote\QuoteService;
use Illuminate\Database\Eloquent\Model;
use App\Task;
use App\NumberGenerator;
use App\Utils\Number;
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
        'customer_id' => 'integer',
        'account_id'  => 'integer',
        'user_id'     => 'integer',
        'line_items'  => 'object',
        'updated_at'  => 'timestamp',
        'deleted_at'  => 'timestamp',
        'is_deleted'  => 'boolean',
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
        'custom_surcharge_tax1',
        'custom_surcharge_tax2',
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

    public function audits()
    {
        return $this->hasManyThrough(Audit::class, Notification::class, 'entity_id');
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

    /********************** Getters and setters ************************************/
    public function setUser(User $user)
    {
        $this->user_id = (int)$user->id;
    }

    public function setDueDate()
    {
        $this->due_date = !empty($this->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $this->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : null;
    }

    public function setAccount(Account $account)
    {
        $this->account_id = (int)$account->id;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer_id = (int)$customer->id;
    }

    public function setStatus(int $status)
    {
        $this->status_id = $status;
    }

    public function setBalance(float $balance)
    {
        $this->balance = (float)$balance;
    }

    public function setTotal(float $total)
    {
        $this->total = (float)$total;
    }

    public function setInvoiceId($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this->customer, $this);
            return true;
        }

        return true;
    }

    public function getFormattedTotal()
    {
        return Number::formatCurrency($this->total, $this->customer);
    }

    public function getFormattedSubtotal()
    {
        return Number::formatCurrency($this->sub_total, $this->customer);
    }

    public function getFormattedBalance()
    {
        return Number::formatCurrency($this->balance, $this->customer);
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getDesignId()
    {
        return !empty($this->design_id) ? $this->design_id : $this->customer->getSetting('quote_design_id');
    }

    public function getPdfFilename()
    {
        return 'storage/' . $this->account->id . '/' . $this->customer->id . '/quotes/' . $this->number . '.pdf';
    }
}
