<?php

namespace App;

use App\Services\Payment\PaymentService;
use App\Services\Transaction\TransactionService;
use Illuminate\Database\Eloquent\Model;
use App\PaymentMethod;
use App\Customer;
use App\Invoice;
use App\Paymentable;
use App\Events\Payment\PaymentWasDeleted;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Money;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use PresentableTrait;
    use SoftDeletes;
    use Money;

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
        'company_gateway_id',
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

    public function service(): PaymentService
    {
        return new PaymentService($this);
    }

    public function transaction_service()
    {
        return new TransactionService($this);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function attachInvoice(Invoice $invoice): Payment
    {
        $this->invoices()->attach(
            $invoice->id,
            [
                'amount' => $this->amount
            ]
        );

        return $this;
    }

    public function attachCredit(Credit $credit): Payment
    {
        $this->credits()->attach(
            $credit->id,
            [
                'amount' => $this->amount
            ]
        );

        return $this;
    }

    public function deletePayment(): bool
    {
        $this->is_deleted = true;
        $this->save();

        $this->delete();

        event(new PaymentWasDeleted($this));

        return true;
    }

    /********************** Getters and setters ************************************/

    public function setStatus(int $status)
    {
        $this->status_id = $status;
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this, $this->customer);
            return true;
        }

        return true;
    }

    public function getFormattedTotal()
    {
        return $this->formatCurrency($this->amount, $this->customer);
    }

    public function getFormattedInvoices()
    {
        $invoice_texts = trans('texts.invoice_number_abbreviated');

        foreach ($this->invoices as $invoice) {
            $invoice_texts .= $invoice->number . ',';
        }

        return substr($invoice_texts, 0, -1);
    }

    /**
     * @param float $amount
     */
    public function applyPayment(float $amount)
    {
        $this->applied += $amount;
        $this->save();
    }
}
