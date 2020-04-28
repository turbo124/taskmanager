<?php

namespace App;

use App\Services\Ledger\LedgerService;
use App\Services\Credit\CreditService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laracasts\Presenter\PresentableTrait;

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
        'custom_surcharge1',
        'custom_surcharge2',
        'custom_surcharge3',
        'custom_surcharge4',
        'custom_surcharge_tax1',
        'custom_surcharge_tax2',
        'custom_surcharge_tax3',
        'custom_surcharge_tax4',
        'design_id'
    ];

    protected $casts = [
        'line_items' => 'object',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    const STATUS_DRAFT = 1;
    const STATUS_SENT = 2;
    const STATUS_PARTIAL = 3;
    const STATUS_APPLIED = 4;

    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function ledger()
    {
        return new LedgerService($this);
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


    /**
     * @return mixed
     */
    public function invoice()
    {
        return $this->belongsTo('App\Invoice')->withTrashed();
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

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)->using(Paymentable::class);
    }

    public function company_ledger()
    {
        return $this->morphMany(CompanyLedger::class, 'company_ledgerable');
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'documentable');
    }

    public function setStatus(int $status)
    {
        $this->status_id = $status;
    }

    public function setBalance($balance) 
    {
        $this->balance = $balance;
    }

    public function setNumber()
    {
        if(!empty($this->number)) {
            return true;
        }

        $this->number = (new NumberGenerator)->getNextNumberForEntity($this->customer, $this);
        return true;
    }
}
