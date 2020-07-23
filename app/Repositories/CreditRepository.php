<?php

namespace App\Repositories;

use App\Models\ClientContact;
use App\Events\Credit\CreditWasCreated;
use App\Events\Credit\CreditWasUpdated;
use App\Filters\CreditFilter;
use App\Jobs\Inventory\ReverseInventory;
use App\Models\NumberGenerator;
use App\Factory\CreditInvitationFactory;
use App\Models\CreditInvitation;
use App\Repositories\Base\BaseRepository;
use App\Models\Credit;
use App\Models\Payment;
use App\Requests\SearchRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\CreditRepositoryInterface;
use App\Models\Customer;
use App\Models\Account;
use Illuminate\Support\Collection;

class CreditRepository extends BaseRepository implements CreditRepositoryInterface
{
    /**
     * PaymentRepository constructor.
     * @param \App\Models\Payment $payment
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
        if ($credit->customer->getSetting('should_update_inventory') === true) {
            ReverseInventory::dispatchNow($credit);
        }

        $credit = $this->save($data, $credit);
        event(new CreditWasCreated($credit));

        return $credit;
    }

    /**
     * @param array $data
     * @param \App\Models\Credit $credit
     * @return Credit|null
     */
    public function updateCreditNote(array $data, Credit $credit): ?Credit
    {
        $credit = $this->save($data, $credit);

        event(new CreditWasUpdated($credit));

        return $credit;
    }

    /**
     * @param array $data
     * @param Credit $credit
     * @return \App\Models\Credit|null
     */
    public function save(array $data, Credit $credit): ?Credit
    {
        $credit->fill($data);
        $credit = $this->populateDefaults($credit);
        $credit = $credit->service()->calculateInvoiceTotals();
        $credit->setNumber();

        $credit->save();

        $this->saveInvitations($credit, 'credit', $data);

        return $credit->fresh();
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
