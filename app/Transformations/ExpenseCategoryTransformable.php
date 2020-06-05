<?php

namespace App\Transformations;

use App\ExpenseCategory;

trait ExpenseCategoryTransformable
{

    /**
     * @param ExpenseCategory $category
     * @return array
     */
    protected function transformCategory(ExpenseCategory $category)
    {
        return [
            'id'        => (int)$category->id,
            'name'      => $category->name,
            'parent_id' => $category->parent_id,
        ];
    }

}
