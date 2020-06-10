<?php

namespace App;

use App\Filters\CreditFilter;
use App\Services\Credit\CreditService;
use App\Services\Transaction\TransactionService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laracasts\Presenter\PresentableTrait;
use App\NumberGenerator;
use App\Traits\Money;

class Credit extends Model
{
    use SoftDeletes;
    use PresentableTrait;

    protected $presenter = 'App\Presenters\CreditPresenter';

    /**
     * @var array
     */
    protected $fillable = [
        'number',
        'customer_id',
        'total',
        'balance',
        'sub_total',
        'tax_total',
        'tax_rate',
        'tax_rate_name',
        'discount_total',
        'is_amount_discount',
        'due_date',
        'status_id',
        'created_at',
        'line_items',
        'po_number',
        'public_notes',
        'private_notes',
        'terms',
        'footer',
        'partial',
        'partial_due_date',
        'date',
        'balance',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'transaction_fee',
        'shipping_cost',
        'transaction_fee_tax',
        'shipping_cost_tax',
        'design_id'
    ];

    protected $casts = [
        'account_id'  => 'integer',
        'user_id'     => 'integer',
        'customer_id' => 'integer',
        'line_items'  => 'object',
        'updated_at'  => 'timestamp',
        'deleted_at'  => 'timestamp',
    ];

    const STATUS_DRAFT = 1;
    const STATUS_SENT = 2;
    const STATUS_PARTIAL = 3;
    const STATUS_APPLIED = 4;

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function transaction_service()
    {
        return new TransactionService($this);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function service(): CreditService
    {
        return new CreditService($this);
    }

    public function invitations()
    {
        return $this->hasMany(CreditInvitation::class);
    }

    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer')->withTrashed();
    }

    public function audits()
    {
        return $this->hasManyThrough(Audit::class, Notification::class, 'entity_id')->where(
            'entity_class',
            '=',
            get_class($this)
        );
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /********************** Getters and setters ************************************/
    public function setTotal(float $total)
    {
        $this->total = (float)$total;
    }

    public function setDueDate()
    {
        $this->due_date = !empty($this->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $this->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : null;
    }

    public function setStatus(int $status)
    {
        $this->status_id = $status;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function setUser(User $user)
    {
        $this->user_id = (int)$user->id;
    }

    public function setAccount(Account $account)
    {
        $this->account_id = (int)$account->id;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer_id = (int)$customer->id;
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

    public function getFormattedTotal()
    {
        return $this->formatCurrency($this->total);
    }

    public function getFormattedSubtotal()
    {
        return $this->formatCurrency($this->sub_total);
    }

    public function getFormattedBalance()
    {
        return $this->formatCurrency($this->balance);
    }

    public function getDesignId()
    {
        return !empty($this->design_id) ? $this->design_id : $this->customer->getSetting('credit_design_id');
    }

    public function getPdfFilename()
    {
        return 'storage/' . $this->account->id . '/' . $this->customer->id . '/credits/' . $this->number . '.pdf';
    }
}
