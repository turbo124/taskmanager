<?php

namespace App\Repositories;

use App\Events\PurchaseOrder\PurchaseOrderWasCreated;
use App\Events\PurchaseOrder\PurchaseOrderWasUpdated;
use App\Filters\PurchaseOrderFilter;
use App\Jobs\Order\QuoteOrders;
use App\Jobs\Product\UpdateProductPrices;
use App\Models\Account;
use App\Models\PurchaseOrder;
use App\Models\Task;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\PurchaseOrderRepositoryInterface;
use App\Requests\SearchRequest;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PurchaseOrderRepository
 * @package App\Repositories
 */
class PurchaseOrderRepository extends BaseRepository implements PurchaseOrderRepositoryInterface
{

    /**
     * PurchaseOrderRepository constructor.
     *
     * @param PurchaseOrder $po
     */
    public function __construct(PurchaseOrder $po)
    {
        parent::__construct($po);
        $this->model = $po;
    }

    /**
     * @param int $id
     * @return PurchaseOrder
     */
    public function findPurchaseOrderById(int $id): PurchaseOrder
    {
        return $this->findOneOrFail($id);
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $data
     * @param Quote $quote
     * @return Quote
     */
    public function createPurchaseOrder(array $data, PurchaseOrder $po): ?PurchaseOrder
    {
        $po = $this->save($data, $po);

        if (!empty($data['recurring'])) {
            $recurring = json_decode($data['recurring'], true);
            $po->service()->createRecurringPurchaseOrder($recurring);
        }


        event(new PurchaseOrderWasCreated($po));

        return $po;
    }

    /**
     * @param array $data
     * @param Quote $quote
     * @return Quote|null
     */
    public function updatePurchaseOrder(array $data, PurchaseOrder $po): ?PurchaseOrder
    {
        $po = $this->save($data, $po);

        event(new PurchaseOrderWasUpdated($po));

        return $po;
    }

    /**
     * @param $data
     * @param Quote $quote
     * @return Quote|null
     */
    public function save($data, PurchaseOrder $po): ?PurchaseOrder
    {
        $po->fill($data);

        $po = $po->service()->calculateInvoiceTotals();
        $po->setNumber();

        $po->save();

        return $po->fresh();
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new PurchaseOrderFilter($this))->filter($search_request, $account);
    }
}
