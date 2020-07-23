<?php
/**
 * Created by PhpStorm.
 * User: michael.hampton
 * Date: 08/12/2019
 * Time: 17:10
 */

namespace App\Models;


use App\Models\AccountUser;
use App\Models\Company;
use App\Models\Country;
use App\Models\Domain;
use App\Events\Account\AccountWasDeleted;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Notifications\Notification;
use App\Services\Account\AccountService;
use App\Models\Credit;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Design;

class Account extends Model
{
    use PresentableTrait, SoftDeletes;

    protected $presenter = 'App\Presenters\AccountPresenter';

    protected $dispatchesEvents = [
        'deleted' => AccountWasDeleted::class,
    ];
    protected $fillable = [
        'industry_id',
        'subdomain',
        'size_id',
        'custom_fields',
        'portal_domain',
        'custom_surcharge_taxes1',
        'custom_surcharge_taxes2',
        'custom_surcharge_taxes3',
        'custom_surcharge_taxes4',
        'first_day_of_week',
        'first_month_of_year',
        'settings'
    ];

    protected $casts = [
        'country_id'    => 'string',
        'custom_fields' => 'object',
        'settings'      => 'object',
        'updated_at'    => 'timestamp',
        'created_at'    => 'timestamp',
        'deleted_at'    => 'timestamp',
    ];

    public function locale()
    {
        return $this->getLocale();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return Language::find($this->settings->language_id);
    }

    public function getLocale()
    {
        return isset($this->settings->language_id) && $this->language()
            ? $this->language()->locale
            : config(
                'taskmanager.locale'
            );
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($setting)
    {
        if (property_exists($this->settings, $setting) != false) {
            return $this->settings->{$setting};
        }

        return null;
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, AccountUser::class, 'company_id', 'id', 'id', 'user_id');
    }

    public function designs()
    {
        return $this->hasMany(Design::class)->whereAccountId($this->id)->orWhere('account_id', null);
    }

    /**
     * @return HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class)->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class)->withTrashed();
    }


    /**
     * @return HasMany
     */
    public function credits()
    {
        return $this->hasMany(Credit::class)->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function customers()
    {
        return $this->hasMany(Customer::class)->withTrashed();
    }

    public function domains()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }


    /**
     * @return mixed
     */
    public function payments()
    {
        return $this->hasMany(Payment::class)->withTrashed();
    }


    /**
     * @return HasMany
     */
    public function tax_rates()
    {
        return $this->hasMany(TaxRate::class);
    }

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function country()
    {
        //return $this->belongsTo(Country::class);
        return Country::find($this->settings->country_id);
    }

    /**
     * @return BelongsTo
     */
    public function getCurrency()
    {
        if (!empty($this->settings->currency_id)) {
            return Currency::whereId($this->settings->currency_id)->first();
        }

        return false;
    }

    public function getLogo()
    {
        return $this->settings->company_logo ?: null;
    }

    public function domain()
    {
        return 'https://' . $this->subdomain . config('taskmanager.app_domain');
    }

    /**
     * @return HasMany
     */
    public function companies()
    {
        return $this->hasMany(Company::class)->withTrashed();
    }

    public function routeNotificationForSlack($notification)
    {
        return $this->slack_webhook_url;
    }

    public function account_users()
    {
        return $this->hasMany(AccountUser::class);
    }

    public function owner()
    {
        $c = $this->account_users->where('is_owner', true)->first();

        return User::find($c->user_id);
    }

    public function service(): AccountService
    {
        return new AccountService($this);
    }
}
