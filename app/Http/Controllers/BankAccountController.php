
<?php

namespace App\Http\Controllers;

use App\Factory\BankAccountFactory;
use App\Repositories\BankAccountRepository;
use App\Requests\Brand\CreateBankAccountRequest;
use App\Requests\Brand\UpdateBankAccountRequest;
use App\Requests\SearchRequest;
use App\Search\BankAccountSearch;
use App\Transformations\BankAccountTransformable;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class BrandController
 * @package App\Http\Controllers
 */
class BankAccountController extends Controller
{
    use BankAccountTransformable;

    /**
     * @var BrandRepository
     */
    private BankAccountRepository $bank_account_repo;

    /**
     * BankAccountController constructor.
     * @param BankAccountRepository $bank_account_repository
     */
    public function __construct(BankAccountRepository $bank_account_repository)
    {
        $this->bank_account_repo = $bank_account_repository;
    }

    /**
     * @return JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $brands = (new BankAccountSearch($this->bank_account_repo))->filter(
            $request,
            auth()->user()->account_user()->account
        );
        return response()->json($brands);
    }

    /**
     * @param CreateBrandRequest $request
     * @return JsonResponse
     */
    public function store(CreateBankAccountRequest $request)
    {
        $bank_account = $this->bank_account_repo->save(
            $request->all(),
            BankAccountFactory::create(auth()->user()->account_user()->account, auth()->user())
        );
        return response()->json($this->transformBankAccount($bank_account));
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateBankAccountRequest $request, $id)
    {
        $bank_account = $this->bank_account_repo->findBankAccountById($id);

        $bank_account_repo = new BankAccountRepository($bank_account);
        $bank_account = $bank_account_repo->save($request->all(), $bank_account);

        return response()->json($this->transformBankAccount($bank_account));
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        $bank_account = $this->bank_account_repo->findBankAccountById($id);

        $bank_account_repo = new BankAccountRepository($bank_account);
        $bank_account_repo->delete();

        return response()->json('deleted');
    }
}
