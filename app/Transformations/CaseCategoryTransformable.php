<?php

namespace App\Transformations;

use App\Models\CaseCategory;

trait CaseCategoryTransformable
{

    /**
     * @param CaseCategory $category
     * @return array
     */
    protected function transformCategory(CaseCategory $category)
    {
        return [
            'id'        => (int)$category->id,
            'name'      => $category->name,
            'parent_id' => $category->parent_id,
        ];
    }

}
