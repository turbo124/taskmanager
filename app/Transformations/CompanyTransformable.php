<?php

namespace App\Transformations;

use App\Company;
use App\CompanyContact;
use App\Transformations\CompanyContactTransformable;

trait CompanyTransformable
{

    /**
     * @param Company $company
     * @return Company
     */
    protected function transformCompany(Company $company)
    {
        $prop = new Company;
        $prop->id = (int)$company->id;
        $prop->name = $company->name;
        $prop->website = $company->website ?: '';
        $prop->created_at = $company->created_at;
        $prop->assigned_user_id = (int)$company->assigned_user_id;
        $prop->user_id = (int)$company->user_id;
        $prop->email = $company->email;
        $prop->deleted_at = $company->deleted_at;
        $prop->phone_number = $company->phone_number;
        $prop->address_1 = $company->address_1;
        $prop->address_2 = $company->address_2;
        $prop->private_notes = $company->private_notes ?: '';
        $prop->town = $company->town;
        $prop->city = $company->city;
        $prop->postcode = $company->postcode;
        $prop->country_id = $company->country_id;
        $prop->currency_id = $company->currency_id;
        $prop->industry_id = (int)$company->industry_id;
        $prop->company_logo = $company->company_logo;
        $prop->custom_value1 = $company->custom_value1 ?: '';
        $prop->custom_value2 = $company->custom_value2 ?: '';
        $prop->custom_value3 = $company->custom_value3 ?: '';
        $prop->custom_value4 = $company->custom_value4 ?: '';
        $prop->contacts = $this->transformContacts($company->contacts);

        return $prop;
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

        return $contacts->map(function (CompanyContact $company_contact) {
            return (new CompanyContactTransformable())->transformCompanyContact($company_contact);
        })->all();
    }

}
