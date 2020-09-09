<?php

namespace App\Http\Controllers;

use App\Factory\CaseTemplateFactory;
use App\Filters\CaseTemplateFilter;
use App\Repositories\CaseTemplateRepository;
use App\Requests\CaseTemplate\CreateCaseTemplateRequest;
use App\Requests\CaseTemplate\UpdateCaseTemplateRequest;
use App\Requests\SearchRequest;
use App\Transformations\CaseTemplateTransformable;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class BrandController
 * @package App\Http\Controllers
 */
class CaseTemplateController extends Controller
{
    use CaseTemplateTransformable;

    /**
     * @var BrandRepository
     */
    private CaseTemplateRepository $brand_repo;

    /**
     * BrandController constructor.
     * @param CaseTemplateRepository $brandRepository
     */
    public function __construct(CaseTemplateRepository $case_template_repo)
    {
        $this->brand_repo = $case_template_repo;
    }

    /**
     * @return JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $templates = (new CaseTemplateFilter($this->brand_repo))->filter(
            $request,
            auth()->user()->account_user()->account
        );
        return response()->json($templates);
    }

    /**
     * @param CreateCaseTemplateRequest $request
     * @return JsonResponse
     */
    public function store(CreateCaseTemplateRequest $request)
    {
        $template = $this->brand_repo->save(
            $request->all(),
            CaseTemplateFactory::create(auth()->user()->account_user()->account, auth()->user())
        );
        return response()->json($this->transformCaseTemplate($template));
    }

    /**
     * @param UpdateCaseTemplateRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateCaseTemplateRequest $request, $id)
    {
        $template = $this->brand_repo->findCaseTemplateById($id);

        $brandRepo = new CaseTemplateRepository($template);
        $brand = $brandRepo->save($request->all(), $template);

        return response()->json($this->transformCaseTemplate($template));
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        $template = $this->brand_repo->findCaseTemplateById($id);

        $brandRepo = new CaseTemplateRepository($template);
        $brandRepo->newDelete($template);

        return response()->json('deleted');
    }
}
