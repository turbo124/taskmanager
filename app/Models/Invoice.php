<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Audit;
use App\Models\Customer;
use App\Models\Email;
use App\Models\File;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use App\Models\NumberGenerator;
use App\Models\Payment;
use App\Services\Invoice\InvoiceService;
use App\Services\Transaction\TransactionService;
use App\Traits\Balancer;
use Illuminate\Database\Eloquent\Model;
use App\Events\Invoice\InvoiceWasDeleted;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Money;

class Invoice extends Model
{

    use PresentableTrait, SoftDeletes, Money, Balancer;

    protected $presenter = 'App\Presenters\InvoicePresenter';

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
        'number',
        'customer_id',
        'assigned_to',
        'total',
        'order_id',
        'balance',
        'sub_total',
        'tax_total',
        'tax_rate',
        'tax_rate_name',
        'discount_total',
        'is_amount_discount',
        'payment_type',
        'due_date',
        'status_id',
        'created_at',
        'start_date',
        'line_items',
        'po_number',
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
        'is_recurring',
        'task_id',
        'company_id',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'transaction_fee',
        'gateway_fee',
        'shipping_cost',
        'transaction_fee_tax',
        'shipping_cost_tax',
        'previous_status',
        'design_id',
        'voucher_code',
        'commission_paid',
        'commission_paid_date',
        'gateway_fee',
        'gateway_percentage',
    ];

    const STATUS_DRAFT = 1;
    const STATUS_SENT = 2;
    const STATUS_PARTIAL = 4;
    const STATUS_PAID = 3;
    const STATUS_CANCELLED = 5;
    const STATUS_REVERSED = 6;
    const LATE_FEE_TYPE = 4;
    const SUBSCRIPTION_TYPE = 5;
    const COMMISSION_TYPE = 2;

    public function service(): InvoiceService
    {
        return new InvoiceService($this);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteInvoice(): bool
    {
        if (!in_array($this->status_id, [self::STATUS_DRAFT, self::STATUS_SENT]) || $this->balance > 0) {
            return false;
        }

        $this->is_deleted = true;
        $this->save();

        $this->delete();

        event(new InvoiceWasDeleted($this));

        return true;
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable')->withPivot('amount', 'refunded')->withTimestamps();
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    public function recurring_invoice()
    {
        return $this->belongsTo(RecurringInvoice::class, 'recurring_invoice_id', 'id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function audits()
    {
        return $this->hasManyThrough(Audit::class, Notification::class, 'entity_id')->where(
            'entity_class',
            '=',
            get_class($this)
        );
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * @return mixed
     */
    public function invitations()
    {
        return $this->hasMany('App\Models\InvoiceInvitation')->orderBy('invoice_invitations.client_contact_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function transaction_service()
    {
        return new TransactionService($this);
    }

    public function isCancellable(): bool
    {
        return in_array(
                $this->status_id,
                [self::STATUS_SENT, self::STATUS_PARTIAL]
            ) && $this->is_deleted === false && $this->deleted_at === null;
    }

    public function isReversable(): bool
    {
        return in_array(
                $this->status_id,
                [self::STATUS_SENT, self::STATUS_PARTIAL, self::STATUS_PAID]
            ) && $this->is_deleted === false && $this->deleted_at === null;
    }

    public function isLocked()
    {
        return ($this->customer->getSetting(
                    'should_lock_invoice'
                ) === 'when_sent' && $this->status_id === self::STATUS_SENT) || ($this->customer->getSetting(
                    'should_lock_invoice'
                ) === 'when_paid' && $this->status_id === self::STATUS_PAID);
    }

    public function resetBalance($amount): bool
    {
        $this->increaseBalance($amount);

        $status = $this->total == $this->balance ? Invoice::STATUS_SENT : Invoice::STATUS_PARTIAL;

        $this->setStatus($status);
        $this->save();

        return true;
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

    public function setDateCancelled()
    {
        $this->date_cancelled = Carbon::now();
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

    public function setPreviousStatus()
    {
        $this->previous_status = $this->status_id;
    }

    public function setPreviousBalance()
    {
        $this->previous_balance = $this->balance;
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this, $this->customer);
            return true;
        }

        return true;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getDesignId()
    {
        return !empty($this->design_id) ? $this->design_id : $this->customer->getSetting('invoice_design_id');
    }

    public function getPdfFilename()
    {
        return 'storage/' . $this->account->id . '/' . $this->customer->id . '/invoices/' . $this->number . '.pdf';
    }

    public function canBeSent()
    {
        return $this->status_id === self::STATUS_DRAFT;
    }
}
