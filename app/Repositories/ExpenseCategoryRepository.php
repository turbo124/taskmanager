<?php

namespace App\Repositories;

use App\Account;
use App\CaseCategory;
use App\ExpenseCategory;
use App\Factory\CategoryFactory;
use App\Repositories\Base\BaseRepository;
use App\Category;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Product;
use App\Transformations\ProductTransformable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ExpenseCategoryRepository extends BaseRepository
{
    /**
     * ExpenseCategoryRepository constructor.
     * @param ExpenseCategory $category
     */
    public function __construct(ExpenseCategory $category)
    {
        parent::__construct($category);
        $this->model = $category;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * List all the categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return Collection
     */
    public function listCategories(
        string $order = 'id',
        string $sort = 'desc',
        Account $account,
        $except = []
    ): Collection {
        return $this->model
            ->where('account_id', '=', $account->id)
            ->orderBy($order, $sort)
            ->get()
            ->except($except);
    }

    /**
     * List all root categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return Collection
     */
    public function rootCategories(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return $this->model->whereIsRoot()->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * @param array $params
     * @param Account $account
     * @return Category
     */
    public function save(array $params, ExpenseCategory $category): ExpenseCategory
    {
        $category->fill($params);

        if (isset($params['parent']) && !empty($params['parent'])) {
            $parent = $this->findCategoryById($params['parent']);
            $category->parent()->associate($parent);
        }

        $category->save();
        return $category;
    }

    /**
     * @param int $id
     * @return Category
     */
    public function findCategoryById(int $id): ExpenseCategory
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Delete a category
     *
     * @return bool
     * @throws Exception
     */
    public function deleteCategory(): bool
    {
        return $this->model->delete();
    }

    /**
     * @return mixed
     */
    public function findParentCategory()
    {
        return $this->model->parent;
    }

    /**
     * @return mixed
     */
    public function findChildren()
    {
        return $this->model->children;
    }

}
