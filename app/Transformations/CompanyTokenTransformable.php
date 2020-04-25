<?php

namespace App\Transformations;


use App\CompanyToken;
use App\Subscription;

trait CompanyTokenTransformable
{

    /**
     * @param CompanyToken $company_token
     * @return CompanyToken
     */
    public function transform(CompanyToken $company_token)
    {
        $prod = new CompanyToken;
        $prod->id = (int)$company_token->id;
        $prod->name = $company_token->name ?: '';
        $prod->updated_at = (int)$company_token->updated_at;
        $prod->archived_at = (int)$company_token->deleted_at;
        $prod->created_at = (int)$company_token->created_at;
        $prod->is_deleted = (bool)$company_token->is_deleted;

        return $prod;
    }
}
