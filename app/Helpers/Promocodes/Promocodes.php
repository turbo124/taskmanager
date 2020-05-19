<?php

namespace App\Helpers\Promocodes;

use App\Account;
use App\Customer;
use App\Order;
use App\User;
use Carbon\Carbon;
use App\Promocode;
use Illuminate\Support\Facades\Log;

class Promocodes
{
    /**
     * Generated codes will be saved here
     * to be validated later.
     *
     * @var array
     */
    private $codes = [];

    /**
     * Length of code will be calculated from asterisks you have
     * set as mask in your config file.
     *
     * @var int
     */
    private $length;

    /**
     * Promocodes constructor.
     */
    public function __construct()
    {
        $this->codes = Promocode::pluck('code')->toArray();
        $this->length = substr_count(config('promocodes.mask'), '*');
    }

    /**
     * Generates promocodes as many as you wish.
     *
     * @param int $amount
     *
     * @return array
     */
    public function output($amount = 1)
    {
        $collection = [];

        for ($i = 1; $i <= $amount; $i++) {
            $random = $this->generate();

            while (!$this->validate($collection, $random)) {
                $random = $this->generate();
            }

            array_push($collection, $random);
        }

        return $collection;
    }

    /**
     * Save promocodes into database
     * Successful insert returns generated promocodes
     * Fail will return empty collection.
     * @param Account $account
     * @param int $amount
     * @param null $reward
     * @param array $data
     * @param null $expires_in
     * @param null $quantity
     * @param bool $is_disposable
     * @return \Illuminate\Support\Collection
     */
    public function create(
        Account $account,
        $amount = 1,
        $reward = null,
        array $data = [],
        $expires_in = null,
        $quantity = null,
        $is_disposable = false,
        $description = ''
    ) {
        $records = [];

        foreach ($this->output($amount) as $code) {
            $records[] = [
                'account_id'    => $account->id,
                'description'   => $description,
                'code'          => $code,
                'reward'        => $reward,
                'data'          => json_encode($data),
                'expires_at'    => $expires_in,
                'is_disposable' => $is_disposable,
                'quantity'      => $quantity,
            ];
        }

        if (Promocode::insert($records)) {
            return collect($records)->map(
                function ($record) {
                    $record['data'] = json_decode($record['data'], true);

                    return $record;
                }
            );
        }

        return collect([]);
    }

    /**
     * Save one-time use promocodes into database
     * Successful insert returns generated promocodes
     * Fail will return empty collection.
     * @param Account $account
     * @param int $amount
     * @param null $reward
     * @param array $data
     * @param null $expires_in
     * @param null $quantity
     * @return \Illuminate\Support\Collection
     */
    public function createDisposable(
        Account $account,
        $amount = 1,
        $reward = null,
        array $data = [],
        $expires_in = null,
        $quantity = null
    ) {
        return $this->create($account, $amount, $reward, $data, $expires_in, $quantity, true);
    }

    /**
     * Check promocode in database if it is valid.
     * @param Account $account
     * @param $code
     * @return bool
     */
    public function check(Account $account, $code, Order $order, Customer $customer)
    {
        Log::emergency($code);

        $promocode = Promocode::byCode($code)->where('account_id', '=', $account->id)->first();

        if ($promocode === null) {
            throw new InvalidPromocodeException;
        }

        if ($promocode->isExpired() || ($promocode->isDisposable() && $promocode->users()->exists(
                )) || $promocode->isOverAmount()) {
            return false;
        }

        if (!empty($promocode->data['scope']) && !$this->validateScope($promocode->data, $order, $customer)) {
            return false;
        }

        return $promocode;
    }

    /**
     * @param $data
     * @param Order $order
     * @param Customer $customer
     * @return bool
     */
    private function validateScope($data, Order $order, Customer $customer)
    {
        switch ($data['scope']) {
            case 'order':

                if ($order->total < $data['scope_value']) {
                    return false;
                }
                break;

            case 'product':
                if (!in_array($data['scope_value'], array_column($order->line_items, 'product_id'))) {
                    return false;
                }
                break;
        }

        return true;
    }

    /**
     * Apply promocode to user that it's used from now.
     * @param Account $account
     * @param $code
     * @param User $user
     * @return Promocode|bool
     */
    public function apply(Order $order, Account $account, $code, Customer $customer)
    {
        try {
            if ($promocode = $this->check($account, $code, $order, $customer)) {
//                if ($this->isSecondUsageAttempt($promocode, $customer)) {
//                    throw new \Exception('already used');
//                }

                $promocode->customers()->attach(
                    $customer->id,
                    [
                        'order_id'     => $order->id,
                        'promocode_id' => $promocode->id,
                        'used_at'      => Carbon::now(),
                    ]
                );

                if (!is_null($promocode->quantity)) {
                    $promocode->quantity -= 1;
                    $promocode->save();
                }

                return $promocode->load('customers');
            }
        } catch (\Exception $exception) {
            //
        }

        return false;
    }

    /**
     * Reedem promocode to user that it's used from now.
     *
     * @param string $code
     *
     * @return bool|Promocode
     * @throws AlreadyUsedException
     * @throws UnauthenticatedException
     */
    public function redeem($code)
    {
        return $this->apply($code);
    }

    /**
     * Expire code as it won't usable anymore.
     *
     * @param string $code
     * @return bool
     * @throws InvalidPromocodeException
     */
    public function disable($code)
    {
        $promocode = Promocode::byCode($code)->first();

        if ($promocode === null) {
            throw new InvalidPromocodeException;
        }

        $promocode->expires_at = Carbon::now();
        $promocode->quantity = 0;

        return $promocode->save();
    }

    /**
     * Clear all expired and used promotion codes
     * that can not be used anymore.
     *
     * @return void
     */
    public function clearRedundant()
    {
        Promocode::all()->each(
            function (Promocode $promocode) {
                if ($promocode->isExpired() || ($promocode->isDisposable() && $promocode->users()->exists(
                        )) || $promocode->isOverAmount()) {
                    $promocode->users()->detach();
                    $promocode->delete();
                }
            }
        );
    }

    /**
     * Get the list of valid promocodes
     *
     * @return Promocode[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Promocode::all()->filter(
            function (Promocode $promocode) {
                return !$promocode->isExpired() && !($promocode->isDisposable() && $promocode->users()->exists(
                        )) && !$promocode->isOverAmount();
            }
        );
    }

    /**
     * Here will be generated single code using your parameters from config.
     *
     * @return string
     */
    private function generate()
    {
        $characters = config('promocodes.characters');
        $mask = config('promocodes.mask');
        $promocode = '';
        $random = [];

        for ($i = 1; $i <= $this->length; $i++) {
            $character = $characters[rand(0, strlen($characters) - 1)];
            $random[] = $character;
        }

        shuffle($random);
        $length = count($random);

        $promocode .= $this->getPrefix();

        for ($i = 0; $i < $length; $i++) {
            $mask = preg_replace('/\*/', $random[$i], $mask, 1);
        }

        $promocode .= $mask;
        $promocode .= $this->getSuffix();

        return $promocode;
    }

    /**
     * Generate prefix with separator for promocode.
     *
     * @return string
     */
    private function getPrefix()
    {
        return (bool)config('promocodes.prefix')
            ? config('promocodes.prefix') . config('promocodes.separator')
            : '';
    }

    /**
     * Generate suffix with separator for promocode.
     *
     * @return string
     */
    private function getSuffix()
    {
        return (bool)config('promocodes.suffix')
            ? config('promocodes.separator') . config('promocodes.suffix')
            : '';
    }

    /**
     * Your code will be validated to be unique for one request.
     *
     * @param $collection
     * @param $new
     *
     * @return bool
     */
    private function validate($collection, $new)
    {
        return !in_array($new, array_merge($collection, $this->codes));
    }

    /**
     * Check if user is trying to apply code again.
     *
     * @param Promocode $promocode
     *
     * @return bool
     */
    public function isSecondUsageAttempt(Promocode $promocode, Customer $customer)
    {
        return $promocode->customers()->wherePivot(
            config('promocodes.related_pivot_key', 'customer_id'),
            $customer->id
        )->exists();
    }
}
