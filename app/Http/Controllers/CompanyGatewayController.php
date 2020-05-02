<?php

namespace App\Http\Controllers;

use App\Factory\CompanyGatewayFactory;
use App\Requests\CompanyGateway\StoreCompanyGatewayRequest;
use App\Requests\CompanyGateway\UpdateCompanyGatewayRequest;
use App\CompanyGateway;
use App\Repositories\AccountRepository;
use App\Repositories\CompanyGatewayRepository;
use App\Settings\GatewaySettings;
use App\Transformations\CompanyGatewayTransformable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

/**
 * Class CompanyGatewayController
 * @package App\Http\Controllers
 */
class CompanyGatewayController extends Controller
{
    use CompanyGatewayTransformable;

    private $account_repo;
    private $company_gateway_repo;

    public $forced_includes = [];

    /**
     * CompanyGatewayController constructor.
     */
    public function __construct(AccountRepository $account_repo, CompanyGatewayRepository $company_gateway_repo)
    {

        $this->account_repo = $account_repo;
        $this->company_gateway_repo = $company_gateway_repo;
    }

    public function index()
    {
        $company_gateways =
            CompanyGateway::whereAccountId(auth()->user()->account_user()->account_id)->get()->keyBy('gateway_key');
        $company_gateways = $company_gateways->map(function (CompanyGateway $company_gateway) {
            return $this->transformCompanyGateway($company_gateway);
        })->all();

        return response()->json($company_gateways);
    }

    public function store(StoreCompanyGatewayRequest $request)
    {
        $company_gateway =
            CompanyGatewayFactory::create(auth()->user()->account_user()->account_id, auth()->user()->id);
        $company_gateway->fill($request->except('fees_and_limits'));
        $company_gateway->save();

        $company_gateway = (new GatewaySettings)->save($company_gateway, $request->fees_and_limits[0]);
        return response()->json($this->transformCompanyGateway($company_gateway));
    }

    /**
     * @param UpdateCompanyGatewayRequest $request
     * @param CompanyGateway $company_gateway
     * @return mixed
     */
    public function update(UpdateCompanyGatewayRequest $request, int $id)
    {
        $company_gateway = $this->company_gateway_repo->findCompanyGatewayById($id);
        $company_gateway->fill($request->except(['fees_and_limits', '_method']));
        $company_gateway->save();

        if ($request->has('fees_and_limits')) {
            $company_gateway = (new GatewaySettings)->save($company_gateway, $request->fees_and_limits[0]);
        }

        return response()->json($this->transformCompanyGateway($company_gateway));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(string $gateway_key)
    {
        $company_gateway = $this->company_gateway_repo->getCompanyGatewayByGatewayKey($gateway_key);

        if (!$company_gateway) {
            return response()->json([]);
        }

        return response()->json($this->transformCompanyGateway($company_gateway));
    }
}
