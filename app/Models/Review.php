<?php


namespace App\Models;


use App\Models\ClientContact;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function contact()
    {
        return $this->belongsTo(ClientContact::class);
    }

}