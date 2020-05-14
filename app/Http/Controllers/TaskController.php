<?php

namespace App\Http\Controllers;

use App\ClientContact;
use App\CompanyToken;
use App\Customer;
use App\Events\Deal\DealWasCreated;
use App\Factory\OrderFactory;
use App\Factory\TaskFactory;
use App\Jobs\Task\SaveTaskTimes;
use App\Project;
use App\Repositories\ClientContactRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Order;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Task;
use App\Requests\Task\CreateTaskRequest;
use App\Requests\Task\CreateDealRequest;
use App\Requests\Task\UpdateTaskRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Product;
use App\Repositories\ProductRepository;
use App\Transformations\TaskTransformable;
use App\Filters\OrderFilter;
use App\Repositories\SourceTypeRepository;
use App\SourceType;
use App\Filters\TaskFilter;
use App\Requests\SearchRequest;

class TaskController extends Controller
{

    use TaskTransformable;

    /**
     * @var TaskRepositoryInterface
     */
    private $task_repo;

    /**
     * @var ProjectRepositoryInterface
     */
    private $project_repo;

    private $task_service;

    /**
     *
     * @param TaskRepositoryInterface $taskRepository
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(TaskRepositoryInterface $task_repo, ProjectRepositoryInterface $project_repo)
    {
        $this->task_repo = $task_repo;
        $this->project_repo = $project_repo;
    }

    public function index(SearchRequest $request)
    {
        $tasks = (new TaskFilter($this->task_repo))->filter($request, auth()->user()->account_user()->account_id);
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateTaskRequest $request)
    {
        $task = $this->task_repo->save(
            $request->all(),
            (new TaskFactory)->create(auth()->user(), auth()->user()->account_user()->account)
        );
        //$task = SaveTaskTimes::dispatchNow($request->all(), $task);
        return response()->json($this->transformTask($task));
    }

    /**
     *
     * @param int $task_id
     * @return type
     */
    public function markAsCompleted(int $task_id)
    {
        $objTask = $this->task_repo->findTaskById($task_id);
        $task = $this->task_repo->save(['is_completed' => true], $task);
        return response()->json($task);
    }

    /**
     *
     * @param int $projectId
     * @return type
     */
    public function getTasksForProject(int $projectId)
    {
        $objProject = $this->project_repo->findProjectById($projectId);
        $list = $this->task_repo->getTasksForProject($objProject);

        $tasks = $list->map(
            function (Task $task) {
                return $this->transformTask($task);
            }
        )->all();

        return response()->json($tasks);
    }

    /**
     * @param UpdateTaskRequest $request
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = $this->task_repo->findTaskById($id);
        $task = $this->task_repo->save($request->all(), $task);
        //$task = SaveTaskTimes::dispatchNow($request->all(), $task);
        return response()->json($task);
    }

    public function getDeals()
    {
        $list = $this->task_repo->getDeals();

        $tasks = $list->map(
            function (Task $task) {
                return $this->transformTask($task);
            }
        )->all();

        return response()->json($tasks);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function updateStatus(Request $request, int $id)
    {
        $task = $this->task_repo->findTaskById($id);
        $task = $this->task_repo->save(['task_status' => $request->task_status], $task);
        return response()->json($task);
    }

    /**
     * @param Request $request
     * @param int $task_type
     * @return mixed
     */
    public function filterTasks(Request $request, int $task_type)
    {
        $tasks = (new TaskFilter($this->task_repo))->filterBySearchCriteria(
            $request->all(),
            $task_type,
            auth()->user()->account_user()->account_id
        );
        return response()->json($tasks);
    }

    public function getTasksWithProducts()
    {
        $tasks = $this->task_repo->getTasksWithProducts();
        return $tasks->toJson();
    }

    /**
     *
     * @param int $task_id
     * @return type
     */
    public function getProducts(int $task_id)
    {
        $products = (new ProductRepository(new Product))->getAll(
            new SearchRequest,
            auth()->user()->account_user()->account
        );
        $task = $this->task_repo->findTaskById($task_id);
        $product_tasks = (new OrderRepository(new Order))->getOrdersForTask($task);

        $arrData = [
            'products' => $products,
            'selectedIds' => $product_tasks->pluck('product_id')->all(),
        ];

        return response()->json($arrData);
    }

    /**
     *
     * @param CreateDealRequest $request
     * @return type
     */
    public function createDeal(Request $request)
    {
        $token_sent = $request->bearerToken();
        $token = CompanyToken::whereToken($token_sent)->first();

        $user = $token->user;
        $account = $token->account;

        $task = (new TaskFactory())->create($user, $account);
        $task = $task->service()->createDeal(
            $request,
            (new CustomerRepository(new Customer)),
            new OrderRepository(new Order),
            new TaskRepository(new Task, new ProjectRepository(new Project)),
            true
        );

        event(new DealWasCreated($task, $task->account));

        return response()->json($task);
    }

    /**
     *
     * @param int $parent_id
     * @return type
     */
    public function getSubtasks(int $parent_id)
    {
        $task = $this->task_repo->findTaskById($parent_id);
        $subtasks = $this->task_repo->getSubtasks($task);

        $tasks = $subtasks->map(
            function (Task $task) {
                return $this->transformTask($task);
            }
        )->all();
        return response()->json($tasks);
    }

    public function getSourceTypes()
    {
        $source_types = (new SourceTypeRepository(new SourceType))->getAll();
        return response()->json($source_types);
    }

    public function getTaskTypes()
    {
        $task_types = (new TaskTypeRepository(new TaskType))->getAll();
        return response()->json($task_types);
    }

    /**
     *
     * @param int $task_id
     * @return type
     */
    public function convertToDeal(int $task_id)
    {
        return response()->json('Unable to convert');
    }

    public function show(int $id)
    {
        $task = $this->task_repo->getTaskById($id);
        return response()->json($this->transformTask($task));
    }


    /**
     * @param $id
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function archive(int $id)
    {
        $task = $this->task_repo->findTaskById($id);
        $task->delete();
    }

    public function destroy(int $id)
    {
        $task = $this->task_repo->findTaskById($id);
        $this->task_repo->newDelete($task);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $task = Task::withTrashed()->where('id', '=', $id)->first();
        $this->task_repo->restore($task);
        return response()->json([], 200);
    }
}
