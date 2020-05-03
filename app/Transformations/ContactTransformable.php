<?php

namespace App\Transformations;

use App\ClientContact;

class ContactTransformable
{
    /**
     * Transform the contact
     * @param ClientContact $contact
     * @return ClientContact
     */
    public function transformClientContact(ClientContact $contact)
    {
        return [
        'id' => $contact->id,
        'first_name' => $contact->first_name ?: '',
        'last_name' => $contact->last_name ?: '',
        'email' => $contact->email ?: '',
        'is_primary' => (bool)$contact->is_primary,
        'phone' => $contact->phone ?: '',
        'custom_value1' => $contact->custom_value1 ?: '',
        'custom_value2' => $contact->custom_value2 ?: '',
        'custom_value3' => $contact->custom_value3 ?: '',
        'custom_value4' => $contact->custom_value4 ?: '',
        'password' => !empty($contact->password) ? '*****' : ''
    ];
    }
}
