<?php

namespace App\Http\Controllers;

use App\Factory\ProjectFactory;
use App\Filters\ProjectFilter;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\ProjectRepository;
use App\Requests\Project\CreateProjectRequest;
use App\Requests\Project\UpdateProjectRequest;
use Illuminate\Support\Facades\Auth;
use App\Transformations\ProjectTransformable;

class ProjectController extends Controller
{
    use ProjectTransformable;

    private $project_repo;

    /**
     *
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(ProjectRepositoryInterface $project_repo)
    {
        $this->project_repo = $project_repo;
    }

    public function index(Request $request)
    {
        $projects =
            (new ProjectFilter($this->project_repo))->filter($request, auth()->user()->account_user()->account_id);
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateProjectRequest $request)
    {
        $project = $this->project_repo->save($request->all(),
            ProjectFactory::create(auth()->user()->id, $request->customer_id,
                auth()->user()->account_user()->account_id));

        return response()->json($this->transformProject($project));
    }

    /**
     * @param UpdateProjectRequest $request
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateProjectRequest $request, int $id)
    {
        $project = $this->project_repo->findProjectById($id);
        $project_repo = new ProjectRepository($project);
        $project = $project_repo->save($request->all(), $project);
        return response()->json($this->transformProject($project));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $project = $this->project_repo->findProjectById($id);
        return response()->json($this->transformProject($project));
    }

    public function markAsCompleted(Project $project)
    {
        $project->is_completed = true;
        $project->update();

        return response()->json('Project updated!');
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $project = Project::withTrashed()->where('id', '=', $id)->first();
        $this->project_repo->restore($project);
        return response()->json([], 200);
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function archive(int $id)
    {
        $project = $this->project_repo->findProjectById($id);
        $project_repo = new ProjectRepository($project);
        //$brandRepo->dissociateProducts();
        $project->delete();
    }

    public function destroy(int $id)
    {
        $project = Project::withTrashed()->where('id', '=', $id)->first();
        $this->project_repo->newDelete($project);
        return response()->json([], 200);
    }
}
