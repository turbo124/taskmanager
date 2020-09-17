<?php

namespace App\Http\Controllers;

use App\Events\Lead\LeadWasCreated;
use App\Factory\Lead\CloneLeadToDealFactory;
use App\Factory\Lead\CloneLeadToTaskFactory;
use App\Factory\LeadFactory;
use App\Filters\LeadFilter;
use App\Models\CompanyToken;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\DealRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Repositories\LeadRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Requests\SearchRequest;
use App\Transformations\DealTransformable;
use App\Transformations\LeadTransformable;
use App\Transformations\TaskTransformable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    use LeadTransformable;
    use DealTransformable;
    use TaskTransformable;

    /**
     * @var CustomerRepositoryInterface
     */
    private $lead_repo;

    /**
     * MessageController constructor.
     * @param MessageRepositoryInterface $messageRepository
     * CustomerRepositoryInterface $customerRepository
     * UserRepositoryInterface $userRepository
     */
    public function __construct(LeadRepository $lead_repo)
    {
        $this->lead_repo = $lead_repo;
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        $leads = (new LeadFilter($this->lead_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($leads);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $token_sent = $request->bearerToken();

        $token = CompanyToken::whereToken($token_sent)->first();

        $user = $token->user;
        $account = $token->account;

        $lead = $this->lead_repo->createLead(LeadFactory::create($account, $user), $request->all());

        event(new LeadWasCreated($lead));
        return response()->json($this->transformLead($lead));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function update(int $id, Request $request)
    {
        $lead = $this->lead_repo->findLeadById($id);
        $lead = $this->lead_repo->updateLead($lead, $request->all());
        return response()->json($lead);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function convert(int $id)
    {
        $lead = $this->lead_repo->findLeadById($id);
        $lead = $lead->service()->convertLead();
        return response()->json($lead);
    }

    public function archive(int $id)
    {
        $lead = $this->lead_repo->findLeadById($id);
        $lead->delete();
    }

    public function destroy(int $id)
    {
        $lead = $this->lead_repo->findLeadById($id);
        $this->lead_repo->newDelete($lead);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $lead = Lead::withTrashed()->where('id', '=', $id)->first();
        $this->lead_repo->restore($lead);
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @param Lead $lead
     * @param $action
     * @return JsonResponse
     * @throws Exception
     */
    public function action(Request $request, Lead $lead, $action)
    {
        switch ($action) {
            case 'clone_lead_to_task':
                $task = (new TaskRepository(new Task(), new ProjectRepository(new Project())))->save(
                    [],
                    CloneLeadToTaskFactory::create(
                        $lead,
                        auth()->user(),
                        auth()->user()->account_user()->account
                    )
                );

                return response()->json($this->transformTask($task));

                break;

            case 'clone_lead_to_deal':
                $deal = (new DealRepository(new Deal()))->save(
                    [],
                    CloneLeadToDealFactory::create($lead, auth()->user(), auth()->user()->account_user()->account),
                );
                return response()->json($this->transformDeal($deal));
                break;
            case 'download': //done
                $disk = config('filesystems.default');
                $content = Storage::disk($disk)->get($lead->service()->generatePdf(null));
                $response = ['data' => base64_encode($content)];
                break;
        }
    }
}
