<?php

namespace App\Jobs\Customer;

use App\Account;
use App\Address;
use App\ClientContact;
use App\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ContactRegister implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private array $data;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * Create a new job instance.
     * ContactRegister constructor.
     * @param array $data
     * @param Account $account
     */
    public function __construct(array $data, Account $account)
    {
        $this->data = $data;
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customer = $this->createCustomer();
        $this->createAddress($customer);
        $this->createShippingAddress($customer);
        $contact = $this->createContact($customer);

        auth()->login($contact);

        return $contact;
    }

    private function createCustomer()
    {
        $customer = new Customer();
        $customer->website = !empty($this->data['website']) ? $this->data['website'] : '';
        $customer->name = !empty($this->data['name']) ? $this->data['name'] : '';
        $customer->account_id = $this->account->id;
        $customer->save();

        return $customer;
    }

    private function createAddress(Customer $customer)
    {
        $address = new Address();
        $address->address_type = 1;
        $address->customer_id = $customer->id;
        $address->fill($this->data);
        $address->save();
    }

    private function createShippingAddress(Customer $customer)
    {
        if (empty($this->data['shipping_address1'])) {
            return true;
        }

        $address = new Address();
        $address->address_type = 2;
        $address->customer_id = $customer->id;
        $address->address_1 = $this->data['shipping_address1'];
        $address->address_2 = $this->data['shipping_address2'];
        $address->city = $this->data['shipping_city'];
        $address->state_code = $this->data['shipping_state'];
        $address->zip = $this->data['shipping_postal_code'];
        $address->country_id = $this->data['shipping_country'];
        $address->save();
    }

    private function createContact(Customer $customer)
    {
        $client_contact = new ClientContact();
        $client_contact->fill($this->data);

        $client_contact->customer_id = $customer->id;
        $client_contact->is_primary = true;
        $client_contact->password = Hash::make($this->data['password']);

        $client_contact->save();

        return $client_contact;
    }
}
