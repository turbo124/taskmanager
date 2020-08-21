<?php

namespace App\Http\Controllers;

use App\Factory\ProductFactory;
use App\Filters\OrderFilter;
use App\Filters\ProductFilter;
use App\Jobs\Customer\StoreProductAttributes;
use App\Jobs\Product\SaveProductFeatures;
use App\Models\CompanyToken;
use App\Models\Order;
use App\Models\Product;
use App\Models\Task;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TaskRepository;
use App\Requests\Product\CreateProductRequest;
use App\Requests\Product\UpdateProductRequest;
use App\Requests\SearchRequest;
use App\Shop\Products\Exceptions\ProductUpdateErrorException;
use App\Transformations\LoanProductTransformable;
use App\Transformations\ProductTransformable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{

    use ProductTransformable, LoanProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $product_repo;

    /**
     * @var CategoryRepositoryInterface
     */
    private $category_repo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $product_repo
     * @param CategoryRepositoryInterface $category_repo
     */
    public function __construct(ProductRepositoryInterface $product_repo, CategoryRepositoryInterface $category_repo)
    {
        $this->product_repo = $product_repo;
        $this->category_repo = $category_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SearchRequest $request)
    {
        $products =
            (new ProductFilter($this->product_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        $product = ProductFactory::create(auth()->user(), auth()->user()->account_user()->account);

        $product = $product->service()->createProduct($this->product_repo, $request->all());

        return $this->transformProduct($product);
    }

    public function show(int $id)
    {
        $product = $this->product_repo->findProductById($id);
        return response()->json($this->transformProduct($product));
    }

    public function find(string $slug)
    {
        $product = $this->product_repo->findProductBySlug($slug);
        return response()->json($this->transformProduct($product));
    }

    /**
     * @param UpdateProductRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->product_repo->findProductById($id);

        $product = $product->service()->createProduct($this->product_repo, $request->all());

        return response()->json($this->transformProduct($product));
    }

    /**
     * @param $id
     * @throws Exception
     */
    public function archive($id)
    {
        $product = $this->product_repo->findProductById($id);
        $productRepo = new ProductRepository($product);
        $productRepo->deleteProduct();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $company = Product::withTrashed()->where('id', '=', $id)->first();
        $this->product_repo->newDelete($company);
        return response()->json([], 200);
    }

    /**
     *
     * @param int $task_id
     * @return type
     */
    public function getProductsForTask(int $task_id, string $status)
    {
        $orderRepository = new OrderRepository(new Order);
        $products =
            (new OrderFilter($orderRepository))->getProductsForTask(
                (new TaskRepository(new Task))->findTaskById($task_id),
                $status
            );
        return response()->json($products);
    }

    /**
     *
     * @param string $slug
     * @return type
     */
    public function getProduct(string $slug)
    {
        $product = $this->product_repo->findProductBySlug(['slug' => $slug]);
        return response()->json($product);
    }

    /**
     *
     * @param int $id
     */
    public function getProductsForCategory(int $id)
    {
        $token_sent = \request()->bearerToken();
        $token = CompanyToken::whereToken($token_sent)->first();
        $account = $token->account;

        $category = $this->category_repo->findCategoryById($id);

        $repo = new CategoryRepository($category);
        $parentCategory = $repo->findParentCategory();

        $list = Product::where('status', '=', 1)
                       ->where('account_id', '=', $account->id)
                       ->orderBy('price', 'asc')
                       ->get();

        $products = $list->map(
            function (Product $product) {
                return $this->transformProduct($product);
            }
        )->all();

        return response()->json(
            [
                'products'        => $products,
                'parent_category' => $parentCategory
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->product_repo->deleteFile($request->only('product', 'image'), 'uploads');
        return response()->json('Image deleted successfully');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function removeThumbnail(Request $request)
    {
        $this->product_repo->deleteThumb($request->input('image'));
        return response()->json('Image deleted successfully');
    }

    public function bulk()
    {
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = Product::withTrashed()->where('id', '=', $id)->first();
        $this->product_repo->restore($group);
        return response()->json([], 200);
    }
}
