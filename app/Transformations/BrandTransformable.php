<?php

namespace App\Transformations;

use App\Brand;
use App\Category;

trait BrandTransformable
{

    /**
     * @param Brand $brand
     * @return array
     */
    protected function transformBrand(Brand $brand)
    {
        return [
            'id'          => (int)$brand->id,
            'name'        => $brand->name,
            'cover'       => $brand->cover,
            'description' => $brand->description,
            'status'      => $brand->status,
        ];
    }

}
