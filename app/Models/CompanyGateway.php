<?php

namespace App\Models;

use App\Models\Gateway;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class CompanyGateway extends Model
{
    protected $casts = [
        'fees_and_limits' => 'object',
        'config'          => 'object',
        'updated_at'      => 'timestamp',
        'created_at'      => 'timestamp',
        'deleted_at'      => 'timestamp',
    ];
    protected $fillable = [
        'gateway_key',
        'accepted_credit_cards',
        'require_cvv',
        'show_billing_address',
        'show_shipping_address',
        'update_details',
        'config',
        'fees_and_limits',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_key', 'key');
    }

    public function resolveRouteBinding($value)
    {
        return $this->where('id', $value)->firstOrFail();
    }
}
