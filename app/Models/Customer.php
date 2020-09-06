<?php

namespace App\Models;

use App\Traits\Balancer;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;

class Customer extends Model implements HasLocalePreference
{

    use SoftDeletes, PresentableTrait, Balancer;

    protected $presenter = 'App\Presenters\CustomerPresenter';

    const CUSTOMER_TYPE_WON = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'balance',
        'name',
        'credits',
        'last_name',
        'status',
        'company_id',
        'currency_id',
        'phone',
        'customer_type',
        'default_payment_method',
        'settings',
        'assigned_to',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'group_settings_id',
        'public_notes',
        'private_notes',
        'website',
        'size_id',
        'industry_id',
        'vat_number'
    ];

    protected $casts = [
        'settings'   => 'object',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
        'is_deleted' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class)->whereStatus(true);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'default_payment_method');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * @return BelongsToMany
     */
    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function error_logs()
    {
        return $this->hasMany(ErrorLog::class)->orderBy('created_at', 'desc');
    }

    public function contacts()
    {
        return $this->hasMany(ClientContact::class)->orderBy('is_primary', 'desc');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function primary_contact()
    {
        return $this->hasMany(ClientContact::class)->whereIsPrimary(true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group_settings()
    {
        return $this->belongsTo(Group::class);
    }

    public function language()
    {
        return Language::find($this->getSetting('language_id'));
    }

    public function locale()
    {
        $language = $this->language();

        return !empty($language) ? $this->language()->locale : 'en';
    }

    public function preferredLocale()
    {
        return $this->locale();
    }

    public function gateways()
    {
        return $this->hasMany(CustomerGateway::class);
    }

    private function checkObjectEmpty($var)
    {
        return is_object($var) && empty((array)$var);
    }

    /**
     * @param $setting
     * @return bool
     */
    public function getSetting($setting)
    {
        /*Client Settings*/
        if (!empty($this->settings->{$setting}) && !$this->checkObjectEmpty($this->settings->{$setting})) {
            return $this->settings->{$setting};
        }

        /*Group Settings*/
        if (!empty($this->group_settings) && !empty($this->group_settings->settings->{$setting}) && !$this->checkObjectEmpty(
                $this->group_settings->settings->{$setting}
            )) {
            return $this->group_settings->settings->{$setting};
        }

        /*Company Settings*/
        if (isset($this->account->settings->{$setting}) && !$this->checkObjectEmpty(
                $this->account->settings->{$setting}
            )) {
            return $this->account->settings->{$setting};
        }

        return false;
    }

    public function getCountryId(): ?Country
    {
        $address = Address::where('address_type', '=', 1)->where('customer_id', '=', $this->id)->first();

        if (!empty($address) && $address->count() > 0) {
            return $address->country;
        }

        return null;
    }

    /**
     * @param float $amount
     */
    public function reducePaidToDateAmount(float $amount)
    {
        $this->paid_to_date -= $amount;
    }

    /**
     * @param float $amount
     */
    public function increasePaidToDateAmount(float $amount)
    {
        $this->paid_to_date += $amount;
    }
}
