<?php

namespace App;

use App\Events\PaymentWasVoided;
use App\Services\Ledger\LedgerService;
use App\Services\Payment\PaymentService;
use Illuminate\Database\Eloquent\Model;
use App\PaymentMethod;
use App\Customer;
use App\Invoice;
use App\Paymentable;
use App\Events\Payment\PaymentWasDeleted;
use Laracasts\Presenter\PresentableTrait;
use Event;
use App\Utils\Number;
use App\Events\PaymentWasRefunded;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use PresentableTrait;
    use SoftDeletes;

    protected $presenter = 'App\Presenters\OrderPresenter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'date',
        'number',
        'type_id',
        'amount',
        'customer_id',
        'status_id',
        'refunded',
        'transaction_reference',
        'is_manual',
        'private_notes',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4'
    ];

    protected $casts = [
        'exchange_rate' => 'float',
        'updated_at'    => 'timestamp',
        'deleted_at'    => 'timestamp',
        'is_deleted'    => 'boolean',
    ];

    protected $with = [
        'paymentables',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    const STATUS_PENDING = 1;
    const STATUS_VOIDED = 2;
    const STATUS_FAILED = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_PARTIALLY_REFUNDED = 5;
    const STATUS_REFUNDED = 6;

    const TYPE_CUSTOMER_CREDIT = 2;

    /**
     * @return BelongsTo
     */
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'type_id');
    }

    public function paymentables()
    {
        return $this->hasMany(Paymentable::class);
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->morphedByMany(Invoice::class, 'paymentable')->withPivot('amount', 'refunded');
    }

    public function invoices()
    {
        return $this->morphedByMany(Invoice::class, 'paymentable')->withPivot('amount')->withTrashed();
    }

    public function credits()
    {
        return $this->morphedByMany(Credit::class, 'paymentable')->withPivot('amount', 'refunded')->withTimestamps();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function company_ledger()
    {
        return $this->morphMany(CompanyLedger::class, 'company_ledgerable');
    }

    public function service(): PaymentService
    {
        return new PaymentService($this);
    }

    public function ledger()
    {
        return new LedgerService($this);
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'documentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function attachInvoice(Invoice $invoice): Payment
    {
        $this->invoices()->attach($invoice->id, [
            'amount' => $this->amount
        ]);

        return $this;
    }

     public function attachCredit(Credit $credit): Payment
    {
        $this->credits()->attach($credit->id, [
            'amount' => $this->amount
        ]);

        return $this;
    }

    public function deletePayment(): bool
    {
        if($this->invoices->count() > 0 && !$this->reversePayment()) {
            return false;
        }

        $this->is_deleted = true;
        $this->save();

        $this->delete();

        event(new PaymentWasDeleted($this));

        return true;
    }

    private function reversePayment (): bool
    {
        $invoices = $this->invoices;
        $customer = $this->customer;
        
        $invoices->each(function ($invoice) {
            if ($invoice->pivot->amount > 0) {
                $invoice->setStatus(Invoice::STATUS_SENT);
                $invoice->setBalance($invoice->pivot->amount);
                $invoice->save();
            }
        });

        $this->ledger()->updateBalance($this->amount);

        $customer->setBalance($this->amount);
        $customer->setPaidToDate($this->amount * -1);
        $customer->save();

        return true;
    }

    public function getFormattedAmount()
    {
        return Number::formatMoney($this->amount, $this->customer);
    }
}
