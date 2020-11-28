<?php

namespace App\Http\Controllers;

use App\Factory\PurchaseOrderFactory;
use App\Models\Company;
use App\Models\PurchaseOrder;
use App\Repositories\CreditRepository;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\PurchaseOrderRepositoryInterface;
use App\Repositories\Interfaces\QuoteRepositoryInterface;
use App\Requests\PurchaseOrder\CreatePurchaseOrderRequest;
use App\Requests\Quote\UpdatePurchaseOrderRequest;
use App\Requests\SearchRequest;
use App\Search\PurchaseOrderSearch;
use App\Transformations\PurchaseOrderTransformable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PurchaseOrderController
 * @package App\Http\Controllers
 */
class PurchaseOrderController extends BaseController
{

    use PurchaseOrderTransformable;

    /**
     * @var PurchaseOrderRepositoryInterface
     */
    private PurchaseOrderRepositoryInterface $purchase_order_repo;


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
        $this->purchase_order_repo = $po_repo;
        parent::__construct($invoice_repo, $quote_repo, $credit_repo, 'PurchaseOrder');
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        $pos = (new PurchaseOrderSearch($this->purchase_order_repo))->filter(
            $request,
            auth()->user()->account_user()->account
        );
        return response()->json($pos);
    }

    /**
     * @param int $quote_id
     * @return mixed
     */
    public function show(int $po_id)
    {
        $po = $this->purchase_order_repo->findPurchaseOrderById($po_id);
        return response()->json($po);
    }

    /**
     * @param CreatePurchaseOrderRequest $request
     * @return mixed
     */
    public function store(CreatePurchaseOrderRequest $request)
    {
        $company = Company::find($request->input('company_id'));
        $po = $this->purchase_order_repo->createPurchaseOrder(
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
        $po = $this->purchase_order_repo->findPurchaseOrderById($id);

        $po = $this->purchase_order_repo->updatePurchaseOrder($request->all(), $po);

        return response()->json($this->transformPurchaseOrder($po));
    }

    /**
     * @param Request $request
     * @param PurchaseOrder $po
     * @param $action
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function action(Request $request, PurchaseOrder $purchase_order, $action)
    {
        return $this->performAction($request, $purchase_order, $action);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $purchase_order = $this->purchase_order_repo->findPurchaseOrderById($id);
        $purchase_order->archive();
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $purchase_order = PurchaseOrder::withTrashed()->where('id', '=', $id)->first();
        $purchase_order->deleteEntity();
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $purchase_order = PurchaseOrder::withTrashed()->where('id', '=', $id)->first();
        $purchase_order->restore();
        return response()->json([], 200);
    }

    public function bulk(Request $request)
    {
    }
}
