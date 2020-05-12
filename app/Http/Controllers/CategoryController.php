<?php

namespace App\Http\Controllers;

use App\CompanyToken;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Requests\CreateCategoryRequest;
use App\Requests\UpdateCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Category;
use App\Transformations\CategoryTransformable;
use App\Requests\SearchRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    use CategoryTransformable;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SearchRequest $request)
    {
        $orderBy = !$request->column ? 'name' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;

        if (request()->has('search_term') && !empty($request->search_term)) {
            $list = $this->categoryRepo->searchCategory(request()->input('search_term'));
        } else {
            $list = $this->categoryRepo->listCategories($orderBy, $orderDir);
        }

        $categories = $list->map(function (Category $category) {
            return $this->transformCategory($category);
        })->all();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->categoryRepo->paginateArrayResults($categories, $recordsPerPage);
            return $paginatedResults->toJson();
        }

        return collect($categories)->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCategoryRequest $request
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $categoryObj = $this->categoryRepo->createCategory($request->except('_token', '_method'), auth()->user()->account_user()->account);
        $category = $this->transformCategory($categoryObj);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = $this->categoryRepo->findCategoryById($id);
        $update = new CategoryRepository($category);
        $update->updateCategory($request->except('_token', '_method'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $category = $this->categoryRepo->findCategoryById($id);
        $category->products()->sync([]);
        $category->delete();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->categoryRepo->deleteFile($request->only('category'));
    }

    public function getRootCategories()
    {

        $categories = $this->categoryRepo->rootCategories();
        return response()->json($categories);
    }

    /**
     *
     * @param string $slug
     * @return type
     */
    public function getCategory(string $slug)
    {
        $token_sent = \request()->bearerToken();
        $token = CompanyToken::whereToken($token_sent)->first();
        $account = $token->account;

        $category = $this->categoryRepo->findCategoryBySlug($slug, $account);
        return response()->json($category);
    }

    /**
     *
     * @param string $slug
     * @return type
     */
    public function getChildCategories(string $slug)
    {
        $token_sent = \request()->bearerToken();
        $token = CompanyToken::whereToken($token_sent)->first();
        $account = $token->account;

        $category = $this->categoryRepo->findCategoryBySlug($slug, $account);
        $categoryRepo = new CategoryRepository($category);
        $categories = $categoryRepo->findChildren();
        return response()->json($categories);
    }

    /**
     *
     * @param int $category_id
     * @return type
     */
    public function getForm(int $category_id)
    {
        $category = $this->categoryRepo->findCategoryById($category_id);
        $form = (new CategoryRepository($category))->getFormForCategory();
        return response()->json($form);
    }
}
