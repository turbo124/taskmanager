<?php

namespace App\Transformations;

use App\Models\Company;
use App\Models\CompanyContact;

trait CompanyTransformable
{

    /**
     * @param Company $company
     * @return array
     */
    protected function transformCompany(Company $company)
    {
        return [
            'id'            => (int)$company->id,
            'number'        => $company->number ?: '',
            'name'          => $company->name,
            'website'       => $company->website ?: '',
            'created_at'    => $company->created_at,
            'assigned_to'   => (int)$company->assigned_to,
            'user_id'       => (int)$company->user_id,
            'email'         => $company->email,
            'deleted_at'    => $company->deleted_at,
            'phone_number'  => $company->phone_number,
            'address_1'     => $company->address_1,
            'address_2'     => $company->address_2,
            'private_notes' => $company->private_notes ?: '',
            'town'          => $company->town,
            'city'          => $company->city,
            'postcode'      => $company->postcode,
            'country_id'    => $company->country_id,
            'currency_id'   => $company->currency_id,
            'industry_id'   => (int)$company->industry_id,
            'company_logo'  => $company->company_logo,
            'custom_value1' => $company->custom_value1 ?: '',
            'custom_value2' => $company->custom_value2 ?: '',
            'custom_value3' => $company->custom_value3 ?: '',
            'custom_value4' => $company->custom_value4 ?: '',
            'contacts'      => $this->transformContacts($company->contacts),

        ];
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
            function (CompanyContact $company_contact) {
                return (new CompanyContactTransformable())->transformCompanyContact($company_contact);
            }
        )->all();
    }

}
