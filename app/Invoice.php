<?php

namespace App;

use App\NumberGenerator;
use App\Services\Invoice\InvoiceService;
use App\Services\Ledger\LedgerService;
use Illuminate\Database\Eloquent\Model;
use App\Events\Invoice\InvoiceWasDeleted;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laracasts\Presenter\PresentableTrait;
use App\Utils\Number;

class Invoice extends Model
{

    use PresentableTrait, SoftDeletes;

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
        'custom_surcharge1',
        'custom_surcharge2',
        'custom_surcharge_tax1',
        'custom_surcharge_tax2',
        'design_id'
    ];

    const STATUS_DRAFT = 1;
    const STATUS_SENT = 2;
    const STATUS_PARTIAL = 4;
    const STATUS_PAID = 3;
    const STATUS_CANCELLED = 5;
    const STATUS_OVERDUE = -1;
    const STATUS_UNPAID = -2;
    const STATUS_REVERSED = 6;

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

    public function company_ledger()
    {
        return $this->morphMany(CompanyLedger::class, 'company_ledgerable');
    }

    public function credits()
    {
        return $this->belongsToMany(Credit::class)->using(Paymentable::class)->withPivot('amount', 'refunded')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable')->withPivot('amount', 'refunded')->withTimestamps();
    }

    /**
     * @return BelongsTo
     */
    public function paymentType()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_type');
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    public function audits()
    {
        return $this->hasManyThrough(Audit::class, Notification::class, 'entity_id')->where(
            'entity_class',
            '=',
            get_class($this)
        );
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'documentable');
    }

    /**
     * @return mixed
     */
    public function invitations()
    {
        return $this->hasMany('App\InvoiceInvitation')->orderBy('invoice_invitations.client_contact_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function ledger()
    {
        return new LedgerService($this);
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

    public function adjustInvoices($amount): bool
    {
        $this->balance += $amount;

        $status = $this->total == $this->balance ? Invoice::STATUS_SENT : Invoice::STATUS_PARTIAL;

        $this->setStatus($status);
        $this->save();

        $this->customer->increaseBalance($amount);
        $this->customer->save();

        if (!$this->updateRefundedAmountForInvoice($amount)) {
            return false;
        }

        return true;
    }

    private function updateRefundedAmountForInvoice($amount): bool
    {
        $paymentable_invoice = Paymentable::wherePaymentableId($this->id)->first();
        $paymentable_invoice->refunded += $amount;
        $paymentable_invoice->save();
        return true;
    }

    /**
     * @param $amount
     */
    public function reduceBalance($amount)
    {
        $this->balance -= floatval($amount);

        if ($this->balance === 0.0) {
            $this->setStatus(self::STATUS_PAID);
        }

        $this->save();
        return $this;
    }

    /**
     * @param float $amount
     * @param float|int $partial_amount
     * @return $this
     */
    public function resetPartialInvoice(float $amount, float $partial_amount = 0)
    {
        $this->balance += floatval($amount);
        $this->partial = $partial_amount > 0 ? $this->partial -= $partial_amount : null;
        $this->partial_due_date = $partial_amount > 0 ? $this->partial_due_date : null;
        $this->status_id = Invoice::STATUS_PARTIAL;
        $this->due_date = Carbon::now()->addDays($this->customer->getSetting('payment_terms'));
        $this->save();
        return $this;
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

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function setTotal(float $total)
    {
        $this->total = (float)$total;
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this->customer, $this);
            return true;
        }

        return true;
    }

    public function getNumber()
    {
        return $this->number;
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

    public function getDesignId()
    {
        return !empty($this->design_id) ? $this->design_id : $this->customer->getSetting('invoice_design_id');
    }

    public function getPdfFilename()
    {
        return 'storage/' . $this->account->id . '/' . $this->customer->id . '/invoices/' . $this->number . '.pdf';
    }
}
