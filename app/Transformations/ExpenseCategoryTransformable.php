<?php

namespace App\Transformations;

use App\Models\ExpenseCategory;

trait ExpenseCategoryTransformable
{

    /**
     * @param ExpenseCategory $category
     * @return array
     */
    protected function transformCategory(ExpenseCategory $category)
    {
        return [
            'id'            => (int)$category->id,
            'name'          => $category->name,
            'parent_id'     => $category->parent_id,
            'expense_count' => $category->expenses->count()
        ];
    }

}
