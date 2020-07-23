<?php

namespace App\Models;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    const ORDERWASCREATED = 1;
    const ORDERWASDELETED = 2;
    const CREDITWASCREATED = 3;
    const CREDITWASDELETED = 4;
    const CUSTOMERWASCREATED = 5;
    const CUSTOMERWASDELETED = 6;
    const INVOICEWASCREATED = 7;
    const INVOICEWASDELETED = 8;
    const PAYMENTWASCREATED = 9;
    const PAYMENTWASDELETED = 10;
    const QUOTEWASCREATED = 11;
    const QUOTEWASDELETED = 12;
    const LEADWASCREATED = 13;
    const ORDERWASBACKORDERED = 14;
    const ORDERWASHELD = 15;

    protected $fillable = [
        'name',
        'target_url',
        'event_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
