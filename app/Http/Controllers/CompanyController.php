<?php

namespace App\Http\Controllers;

use App\Factory\CompanyFactory;
use App\Filters\CompanyFilter;
use App\Models\Company;
use App\Models\Industry;
use App\Repositories\CompanyContactRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Requests\Company\CreateCompanyRequest;
use App\Requests\Company\UpdateCompanyRequest;
use App\Requests\SearchRequest;
use App\Settings\CompanySettings;
use App\Traits\UploadableTrait;
use App\Transformations\CompanyTransformable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CompanyController extends Controller
{

    use CompanyTransformable, UploadableTrait;

    /**
     * @var CompanyRepositoryInterface
     */
    private $company_repo;

    /**
     * @var CompanyContactRepository
     */
    private $company_contact_repo;

    /**
     * CompanyController constructor.
     * @param CompanyRepositoryInterface $company_repo
     */
    public function __construct(
        CompanyRepositoryInterface $company_repo,
        CompanyContactRepository $company_contact_repo
    ) {
        $this->company_repo = $company_repo;
        $this->company_contact_repo = $company_contact_repo;
    }

    /**
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $brands =
            (new CompanyFilter($this->company_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($brands);
    }

    /**
     * @param CreateCompanyRequest $request
     * @return JsonResponse
     */
    public function store(CreateCompanyRequest $request)
    {
        $company = (new CompanyFactory)->create(auth()->user(), auth()->user()->account_user()->account);

        $company = $this->company_repo->save($request->except('logo'), $company);

        if (!empty($request->contacts)) {
            $this->company_contact_repo->save($request->contacts, $company);
        }

        if ($request->company_logo !== null) {
            $logo_path = $this->uploadLogo($request->file('company_logo'));
            $settings['company_logo'] = $logo_path;
        }

        $company = (new CompanySettings)->save($company, (object)$settings);

        return response()->json($this->transformCompany($company));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $brand = $this->company_repo->findCompanyById($id);
        return response()->json($this->transformCompany($brand));
    }

    /**
     * @param UpdateCompanyRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateCompanyRequest $request, $id)
    {
        $company = $this->company_repo->findCompanyById($id);

        $this->company_repo->save($request->all(), $company);

        if (!empty($request->contacts)) {
            $this->company_contact_repo->save($request->contacts, $company);
        }

        if ($request->company_logo !== null && $request->company_logo !== 'null') {
            $logo_path = $this->uploadLogo($request->file('company_logo'));
            $settings = $company->settings;
            $settings->company_logo = $logo_path;

            $company = (new CompanySettings)->save($company, (object)$settings);
        }

        return response()->json($this->transformCompany($company));
    }


    public function getIndustries()
    {
        $industries = Industry::all();
        return response()->json($industries);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = Company::withTrashed()->where('id', '=', $id)->first();
        $this->company_repo->restore($group);
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
        $brand = $this->company_repo->findCompanyById($id);
        $brandRepo = new CompanyRepository($brand);
        //$brandRepo->dissociateProducts();
        $brandRepo->deleteCompany();
    }

    public function destroy(int $id)
    {
        $company = Company::withTrashed()->where('id', '=', $id)->first();
        $this->company_repo->newDelete($company);
        return response()->json([], 200);
    }


}
