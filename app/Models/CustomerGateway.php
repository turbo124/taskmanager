<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Archiveable;

class CustomerGateway extends Model
{
    use HasFactory;
    use Archiveable;

    public function company_gateway()
    {
        return $this->hasOne(CompanyGateway::class, 'id', 'company_gateway_id');
    }
}
