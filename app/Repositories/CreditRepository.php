<?php

namespace App\Repositories;

use App\Events\Credit\CreditWasCreated;
use App\Events\Credit\CreditWasUpdated;
use App\Filters\CreditFilter;
use App\Jobs\Inventory\ReverseInventory;
use App\Models\Account;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\Payment;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\CreditRepositoryInterface;
use App\Requests\SearchRequest;
use App\Traits\BuildVariables;
use Illuminate\Support\Collection;

class CreditRepository extends BaseRepository implements CreditRepositoryInterface
{
    use BuildVariables;

    /**
     * PaymentRepository constructor.
     * @param Payment $payment
     */
    public function __construct(Credit $credit)
    {
        parent::__construct($credit);
        $this->model = $credit;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $data
     * @param Credit $credit
     * @return Credit|null
     */
    public function createCreditNote(array $data, Credit $credit): ?Credit
    {
        if (!empty($data['return_to_stock']) && $credit->customer->getSetting('should_update_inventory') === true) {
            ReverseInventory::dispatchNow($credit);
        }

        $credit = $this->save($data, $credit);
        event(new CreditWasCreated($credit));

        return $credit;
    }

    /**
     * @param array $data
     * @param Credit $credit
     * @return Credit|null
     */
    public function save(array $data, Credit $credit): ?Credit
    {
        $credit->fill($data);
        $credit = $this->populateDefaults($credit);
        $credit = $this->formatNotes($credit);
        $credit = $credit->service()->calculateInvoiceTotals();
        $credit->setNumber();

        $credit->save();

        $this->saveInvitations($credit, 'credit', $data);

        return $credit->fresh();
    }

    /**
     * @param array $data
     * @param Credit $credit
     * @return Credit|null
     */
    public function updateCreditNote(array $data, Credit $credit): ?Credit
    {
        $credit = $this->save($data, $credit);

        event(new CreditWasUpdated($credit));

        return $credit;
    }

    public function getCreditForCustomer(Customer $objCustomer)
    {
        return $this->model->where('customer_id', $objCustomer->id)->get();
    }

    /**
     * @param int $id
     * @return Credit
     */
    public function findCreditById(int $id): Credit
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return Collection
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new CreditFilter($this))->filter($search_request, $account);
    }

    /**
     * @param int $status
     * @return Collection
     */
    public function findCreditsByStatus(int $status): Collection
    {
        return $this->model->where('status_id', '=', $status)->get();
    }
}
