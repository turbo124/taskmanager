<?php

namespace App\Models;

use App\Models;
use App\Models\NumberGenerator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use App\Models\User;

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
    ];


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
        return $this->belongsTo(Models\User::class, 'assigned_user_id', 'id');
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

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this, $this->customer);
            return true;
        }

        return true;
    }
}
