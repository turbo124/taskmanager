<?php

namespace App\Models;

use App\CompanyUser;
use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;

class Company extends Model
{

    use PresentableTrait;
    use SoftDeletes;

    protected $fillable = [
        'number',
        'name',
        'website',
        'phone_number',
        'email',
        'address_1',
        'address_2',
        'town',
        'city',
        'postcode',
        'assigned_to',
        'country_id',
        'currency_id',
        'settings',
        'industry_id',
        'private_notes',
        'assigned_to',
        'user_id',
        'account_id',
        'transaction_name',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
    ];

    protected $casts = [
        'settings'   => 'object',
        'is_deleted' => 'boolean',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $with = [
        'contacts',
    ];

    protected $presenter = 'App\Presenters\CompanyPresenter';

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Models\Product::class);
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(CompanyContact::class)->orderBy('is_primary', 'desc');
    }

    public function setNumber()
    {
        if (empty($this->number) || !isset($this->id)) {
            $this->number = (new NumberGenerator)->getNextNumberForEntity($this);
            return true;
        }

        return true;
    }

}
