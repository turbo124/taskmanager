<?php

namespace App\Transformations;

use App\Category;

trait CategoryTransformable
{

    protected function transformCategory(Category $category)
    {
        return [
            'id'          => (int)$category->id,
            'name'        => $category->name,
            'description' => $category->description,
            'slug'        => $category->slug,
            'parent_id'   => $category->parent_id,
            'status'      => $category->status,
        ];
    }

}
