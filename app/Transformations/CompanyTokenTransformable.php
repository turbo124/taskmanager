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
        return [
        'id' => (int)$company_token->id,
        'name' => $company_token->name ?: '',
        'updated_at' => (int)$company_token->updated_at,
        'archived_at' => (int)$company_token->deleted_at,
        'created_at' => (int)$company_token->created_at,
        'is_deleted' => (bool)$company_token->is_deleted,
        ];
    }
}
