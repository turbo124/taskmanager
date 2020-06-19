
<?php

namespace App\Factory\Account;

use App\Address;
use App\ClientContact;
use App\Customer;
use App\Account;

class CloneAccountToAddressFactory
{
    public static function create(Account $account, Customer $customer): Address
    {
        $address = new Address();
        $address->address_1 = $account->address_1;
        $address->address_2 = $account->address_2;
        $address->customer_id = $customer->id;
        $address->zip = $account->zip;
        $address->city = $account->city;
        $address->country_id = 225;
        $address->address_type = 1;
        $address->status = 1;


        return $address;
    }
}
