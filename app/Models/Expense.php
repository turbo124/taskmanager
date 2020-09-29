<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'customer_id',
        'status_id',
        'company_id',
        'currency_id',
        'date',
        'invoice_currency_id',
        'amount',
        'converted_amount',
        'exchange_rate',
        'public_notes',
        'private_notes',
        'bank_id',
        'transaction_id',
        'category_id',
        'tax_rate1',
        'tax_name1',
        'payment_date',
        'payment_type_id',
        'transaction_reference',
        'include_documents',
        'create_invoice',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'is_recurring',
        'recurring_start_date',
        'recurring_end_date',
        'recurring_due_date',
        'last_sent_date',
        'next_send_date',
        'recurring_frequency'
    ];

    const STATUS_LOGGED = 1;
    const STATUS_PENDING = 2;
    const STATUS_INVOICED = 3;

    protected $casts = [
        'is_deleted' => 'boolean',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];


    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function assigned_user()
    {
        return $this->belongsTo(Models\User::class, 'assigned_to', 'id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class)->withTrashed();
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this, $this->customer);
            return true;
        }

        return true;
    }

    /**
     * @param int $status_id
     * @return bool
     */
    public function setStatus(int $status_id)
    {
        $this->status_id = $status_id;
        return true;
    }
}
