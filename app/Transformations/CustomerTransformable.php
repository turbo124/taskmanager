<?php

namespace App\Transformations;

use App\Address;
use App\ClientContact;
use App\Customer;
use App\Transaction;

trait CustomerTransformable
{
    /**
     * @param Customer $customer
     * @return array
     * @throws \Exception
     */
    protected function transformCustomer(Customer $customer)
    {
        $company = !empty($customer->company_id) ? $customer->company->toArray() : '';
        $credit = $customer->credits()->count() > 0 ? $customer->credits->first()->amount : 0;

        $addresses = $this->transformAddress($customer->addresses);

        $billing = null;
        $shipping = null;

        foreach ($addresses as $address) {
            if ($address->address_type === 1) {
                $billing = $address;
            } elseif ($address->address_type === 2) {
                $shipping = $address;
            }
        }

        return [
            'id'                     => (int)$customer->id,
            'user_id'                => (int)$customer->user_id,
            'number'                 => $customer->number,
            'created_at'             => $customer->created_at,
            'name'                   => $customer->name,
            'phone'                  => $customer->phone,
            'company_id'             => $customer->company_id,
            'deleted_at'             => $customer->deleted_at,
            'company'                => $company,
            'credit'                 => $credit,
            'contacts'               => $this->transformContacts($customer->contacts),
            'default_payment_method' => $customer->default_payment_method,
            'group_settings_id'      => $customer->group_settings_id,
            'shipping'               => $shipping,
            'billing'                => $billing,
            'website'                => $customer->website ?: '',
            'vat_number'             => $customer->vat_number ?: '',
            'industry_id'            => (int)$customer->industry_id ?: null,
            'size_id'                => (int)$customer->size_id ?: null,
            'currency_id'            => $customer->currency_id,
            'balance'                => (float)$customer->balance,
            'paid_to_date'           => (float)$customer->paid_to_date,
            'credit_balance'         => (float)$customer->credit_balance,
            'assigned_user'          => $customer->assigned_user_id,
            'settings'               => [
                'payment_terms' => $customer->getSetting('payment_terms')
            ],
            'transactions'           => $this->transformTransactions($customer->transactions),
            'custom_value1'          => $customer->custom_value1 ?: '',
            'custom_value2'          => $customer->custom_value2 ?: '',
            'custom_value3'          => $customer->custom_value3 ?: '',
            'custom_value4'          => $customer->custom_value4 ?: '',
            'private_notes'          => $customer->private_notes ?: '',
            'public_notes'           => $customer->public_notes ?: '',
        ];
    }

    /**
     * @param $transactions
     * @return array
     */
    private function transformTransactions($transactions)
    {
        if (empty($transactions)) {
            return [];
        }

        return $transactions->map(
            function (Transaction $transaction) {
                return (new TransactionTransformable())->transformTransaction($transaction);
            }
        )->all();
    }

    /**
     * @param $contacts
     * @return array
     */
    private function transformContacts($contacts)
    {
        if (empty($contacts)) {
            return [];
        }

        return $contacts->map(
            function (ClientContact $contact) {
                return (new ContactTransformable())->transformClientContact($contact);
            }
        )->all();
    }

    /**
     * @param $addresses
     * @return array
     */
    private function transformAddress($addresses)
    {
        if (empty($addresses)) {
            return [];
        }

        return $addresses->map(
            function (Address $address) {
                return (new AddressTransformable())->transformAddress($address);
            }
        )->all();
    }
}
