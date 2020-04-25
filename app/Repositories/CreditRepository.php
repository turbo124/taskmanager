<?php

namespace App\Repositories;

use App\ClientContact;
use App\NumberGenerator;
use App\Factory\CreditInvitationFactory;
use App\CreditInvitation;
use App\Repositories\Base\BaseRepository;
use App\Credit;
use App\Payment;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\CreditRepositoryInterface;
use App\Libraries\Utils;
use App\Customer;
use Illuminate\Support\Collection;

class CreditRepository extends BaseRepository implements CreditRepositoryInterface
{
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
    public function save(array $data, Credit $credit): ?Credit
    {
        $credit->fill($data);
        $credit = $this->populateDefaults($credit);
        $credit = $credit->service()->calculateInvoiceTotals();
        
        if(empty($credit->number)) {
            $credit->number = (new NumberGenerator)->getNextNumberForEntity($credit->customer, $credit);
        }

        $credit->save();

        $this->saveInvitations($credit, 'credit', $data);

        return $credit->fresh();
    }

    public function getCreditForCustomer(Customer $objCustomer)
    {
        return $this->model->where('customer_id', $objCustomer->id)->get();
    }

    public function getInvitationByKey($key): ?CreditInvitation
    {
        return CreditInvitation::whereRaw("BINARY `key`= ?", [$key])->first();
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
     * List all the categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return Collection
     */
    public function listCredits(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return $this->model->orderBy($order, $sort)->get()->except($except);
    }
}
