<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Quote;
use App\Models\Task;
use App\Repositories\DealRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\LeadRepository;
use App\Requests\SearchRequest;
use App\Search\LeadSearch;
use App\Transformations\TaskTransformable;

class DashboardController extends Controller
{

    use TaskTransformable;

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * DashboardController constructor.
     *
     * TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $deal_repo = new DealRepository(new Deal);
        $arrSources = $this->taskRepository->getSourceTypeCounts(3, auth()->user()->account_user()->account_id);
        $arrStatuses = $this->taskRepository->getStatusCounts(auth()->user()->account_user()->account_id);
        $leadsToday = $this->taskRepository->getRecentTasks(3, auth()->user()->account_user()->account_id);
        $customersToday = $this->customerRepository->getRecentCustomers(3, auth()->user()->account_user()->account_id);
        $newDeals = $deal_repo->getNewDeals(3, auth()->user()->account_user()->account_id);
        $leads = (new LeadSearch(new LeadRepository(new Lead())))->filter(
            new SearchRequest(),
            auth()->user()->account_user()->account
        );
        $totalEarnt = $deal_repo->getTotalEarnt(auth()->user()->account_user()->account_id);

        $arrOutput = [
            'customers'    => Customer::all(),
            'sources'      => $arrSources->toArray(),
            'leadCounts'   => $arrStatuses->toArray(),
            'totalBudget'  => number_format($totalEarnt, 2),
            'totalEarnt'   => number_format($totalEarnt, 2),
            'leadsToday'   => number_format($leadsToday, 2),
            'newDeals'     => number_format($newDeals, 2),
            'newCustomers' => number_format($customersToday, 2),
            'deals'        => $leads,
            'invoices'     => Invoice::all()->where('account_id', auth()->user()->account_user()->account_id),
            'quotes'       => Quote::all()->where('account_id', auth()->user()->account_user()->account_id),
            'credits'      => Credit::all()->where('account_id', auth()->user()->account_user()->account_id),
            'payments'     => Payment::all()->where('account_id', auth()->user()->account_user()->account_id),
            'orders'       => Order::all()->where('account_id', auth()->user()->account_user()->account_id),
            'expenses'     => Expense::all()->where('account_id', auth()->user()->account_user()->account_id),
            'tasks'        => Task::all()->where('account_id', auth()->user()->account_user()->account_id),
        ];

        return response()->json($arrOutput);
    }

}
