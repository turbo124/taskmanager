<?php

namespace App\Http\Controllers;

use App\Factory\QuoteFactory;
use App\Filters\QuoteFilter;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\Task;
use App\Repositories\CreditRepository;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\QuoteRepositoryInterface;
use App\Repositories\TaskRepository;
use App\Requests\Quote\CreateQuoteRequest;
use App\Requests\Quote\UpdateOrderRequest;
use App\Requests\Quote\UpdateQuoteRequest;
use App\Requests\SearchRequest;
use App\Transformations\InvoiceTransformable;
use App\Transformations\QuoteTransformable;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PurchaseOrderController
 * @package App\Http\Controllers
 */
class PurchaseOrderController extends BaseController
{

    use PurchaseOrderTransformable, InvoiceTransformable;

    /**
     * @var PurchaseOrderRepositoryInterface
     */
    private $po_repo;

    
    /**
     * PurchaseOrderController constructor.
     * @param InvoiceRepositoryInterface $invoice_repo
     * @param QuoteRepositoryInterface $quote_repo
     */
    public function __construct(
        PurchaseOrderRepositoryInterface $po_repo,
        InvoiceRepositoryInterface $invoice_repo,
        QuoteRepositoryInterface $quote_repo,
        CreditRepository $credit_repo
    ) {
        $this->invoice_repo = $invoice_repo;
        $this->quote_repo = $quote_repo;
        $this->po_repo = $po_repo;
        parent::__construct($invoice_repo, $quote_repo, $credit_repo, 'Quote');
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        $pos = (new PurchaseOrderFilter($this->po_repo))->filter($request, auth()->user()->account_user()->account);
        return response()->json($pos);
    }

    /**
     * @param int $quote_id
     * @return mixed
     */
    public function show(int $po_id)
    {
        $po = $this->po_repo->findPurchaseOrderById($po_id);
        return response()->json($po);
    }

    /**
     * @param CreatePurchaseOrderRequest $request
     * @return mixed
     */
    public function store(CreatePurchaseOrderRequest $request)
    {
        $company = Company::find($request->input('company_id'));
        $po = $this->po_repo->createPurchaseOrder(
            $request->all(),
            PurchaseOrderFactory::create(auth()->user()->account_user()->account, auth()->user(), $company)
        );

        return response()->json($this->transformPurchaseOrder($po));
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update(UpdatePurchaseOrderRequest $request, int $id)
    {
        $po = $this->po_repo->findPurchaseOrderById($id);

        $po = $this->po_repo->updatePurchaseOrder($request->all(), $po);

        return response()->json($this->transformPurchaseOrder($po));
    }

    /**
     * @param Request $request
     * @param PurchaseOrder $po
     * @param $action
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function action(Request $request, PurchaseOrder $po, $action)
    {
        return $this->performAction($request, $po, $action);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $po = $this->po_repo->findPurchaseOrderById($id);
        $this->po_repo->archive($po);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $po = PurchaseOrder::withTrashed()->where('id', '=', $id)->first();
        $this->po_repo->newDelete($po);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $po = PurchaseOrder::withTrashed()->where('id', '=', $id)->first();
        $this->po_repo->restore($po);
        return response()->json([], 200);
    }

    public function bulk(Request $request)
    {
    }
}
