<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerGateway extends Model
{
    public function company_gateway()
    {
        return $this->hasOne(CompanyGateway::class, 'id', 'company_gateway_id');
    }
}