<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGateway extends Model
{
    use HasFactory;

    public function company_gateway()
    {
        return $this->hasOne(CompanyGateway::class, 'id', 'company_gateway_id');
    }
}