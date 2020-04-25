<?php


namespace App\Factory;


use App\Category;

class CategoryFactory
{
    public static function create()
    {
        $category = new Category;
        $category->cover = '';
        $category->name = '';
        $category->slug = '';
        $category->description = '';
        $category->status = 1;

        return $category;
    }
}