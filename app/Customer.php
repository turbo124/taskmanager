<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Contracts\Translation\HasLocalePreference;

class Customer extends Model implements HasLocalePreference
{

    use SoftDeletes, PresentableTrait;

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
        'assigned_user_id',
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

    public function contacts()
    {
        return $this->hasMany(ClientContact::class)->orderBy('is_primary', 'desc');
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
        return $this->belongsTo(GroupSetting::class);
    }

    public function language()
    {
        return Language::find($this->getSetting('language_id'));
    }

    public function locale()
    {
        return $this->language()->locale ?: 'en';
    }

    public function preferredLocale()
    {
        return $this->locale();
    }

    /**
     *
     * Returns a single setting
     * which cascades from
     * Client > Group > Company
     *
     * @param string $setting The Setting parameter
     * @return mixed          The setting requested
     */
    public function getSetting($setting)
    {
        /*Client Settings*/
        if (!empty($this->settings->{$setting})) {
            return $this->settings->{$setting};
        }

        /*Group Settings*/
        if ($this->group_settings && !empty($this->group_settings->settings->{$setting})) {
            return $this->group_settings->settings->{$setting};
        }

        /*Company Settings*/
        if (isset($this->account->settings->{$setting})) {
            return $this->account->settings->{$setting};
        }

        throw new \Exception("Settings corrupted", 1);
    }

    public function getCountryId(): ?Country
    {
        $address = Address::where('address_type', '=', 1)->where('customer_id', '=', $this->id)->first();

        if (!empty($address) && $address->count() > 0) {
            return $address->country;
        }

        return null;
    }

    public function increaseBalance(float $amount)
    {
        $this->balance += $amount;
    }

    public function increasePaidToDateAmount(float $amount)
    {
        $this->paid_to_date += $amount;
    }
}
