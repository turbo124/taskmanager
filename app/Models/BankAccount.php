<?php
/**
 * Created by PhpStorm.
 * User: michael.hampton
 * Date: 08/12/2019
 * Time: 17:10
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use SoftDeletes;

    public function children(){
        return $this->hasMany( 'App\Models\BankAccount', 'parent_id', 'id' );
    }

    public function parent(){
        return $this->hasOne( 'App\Models\BankAccount', 'id', 'parent_id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }
}
