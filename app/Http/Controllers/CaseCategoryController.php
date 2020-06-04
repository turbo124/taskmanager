<?php

namespace App\Http\Controllers;

use App\Factory\CaseCategoryFactory;
use App\Factory\ExpenseCategoryFactory;
use App\Filters\CaseCategoryFilter;
use App\Repositories\CaseCategoryRepository;
use App\Repositories\ExpenseCategoryRepository;
use App\Category;
use App\Requests\CaseCategory\CreateCategoryRequest;
use App\Requests\CaseCategory\UpdateCategoryRequest;
use App\Transformations\CaseCategoryTransformable;
use App\Requests\SearchRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CaseCategoryController extends Controller
{

    use CaseCategoryTransformable;

    /**
     * @var CaseCategoryRepository
     */
    private CaseCategoryRepository $category_repo;

    /**
     * CaseCategoryController constructor.
     * @param CaseCategoryRepository $categoryRepository
     */
    public function __construct(CaseCategoryRepository $categoryRepository)
    {
        $this->category_repo = $categoryRepository;
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $categories = (new CaseCategoryFilter($this->category_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($categories);
    }

    /**
     * @param CreateCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = $this->category_repo->save(
            $request->all(),
            CaseCategoryFactory::create(auth()->user()->account_user()->account, auth()->user())
        );

        return response()->json($this->transformCategory($category));
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param int $id
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = $this->category_repo->findCategoryById($id);
        $update = new CaseCategoryRepository($category);
        $update->save($request->except('_token', '_method'), $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $category = $this->category_repo->findCategoryById($id);
        $category->delete();
    }

    public function getRootCategories()
    {
        $categories = $this->category_repo->rootCategories();
        return response()->json($categories);
    }
}
