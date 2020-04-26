<?php

namespace App\Http\Controllers;

use App\Factory\CloneRecurringQuoteFactory;
use App\Factory\CloneRecurringQuoteToQuoteFactory;
use App\Factory\RecurringQuoteFactory;
use App\Filters\RecurringInvoiceFilter;
use App\Filters\RecurringQuoteFilter;
use App\Invoice;
use App\Jobs\Invoice\CreateInvoicePdf;
use App\Jobs\RecurringInvoice\SendRecurring;
use App\RecurringQuote;
use App\Quote;
use App\Customer;
use App\Repositories\BaseRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\RecurringQuoteRepository;
use App\Requests\SearchRequest;
use App\Requests\RecurringQuote\StoreRecurringQuoteRequest;
use App\Transformations\RecurringQuoteTransformable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class RecurringQuoteController
 * @package App\Http\Controllers\RecurringQuoteController
 */
class RecurringQuoteController extends Controller
{
    use RecurringQuoteTransformable;

    /**
     * @var RecurringQuoteRepository
     */
    protected $recurring_quote_repo;

    /**
     * RecurringQuoteController constructor.
     * @param RecurringQuoteRepository $recurring_quote_repo
     */
    public function __construct(RecurringQuoteRepository $recurring_quote_repo)
    {
        $this->recurring_quote_repo = $recurring_quote_repo;
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {

        $invoices = (new RecurringQuoteFilter($this->recurring_quote_repo))->filter($request,
            auth()->user()->account_user()->account_id);
        return response()->json($invoices);
    }

    /**
     * @param StoreRecurringQuoteRequest $request
     * @return mixed
     * @throws Exception
     */
    public function store(StoreRecurringQuoteRequest $request)
    {

        $quote = (new QuoteRepository(new Quote()))->findQuoteById($request->quote_id);

        $arrRecurring = array_merge(array(
            'sub_total'      => $quote->sub_total,
            'tax_total'      => $quote->tax_total,
            'discount_total' => $quote->discount_total,
            'date'           => $quote->date,
            'due_date'       => $quote->due_date,
            'line_items'     => $quote->line_items,
            'footer'         => $quote->footer,
            'notes'          => $quote->notes,
            'terms'          => $quote->terms,
            'total'          => $quote->total,
            'partial'        => $quote->partial
        ), $request->all());

        $recurring_quote = (new RecurringQuoteRepository(new RecurringQuote))->save($arrRecurring,
            RecurringQuoteFactory::create(Customer::where('id', $request->customer_id)->first(), auth()->user()->account_user()->account,
                auth()->user(), $quote->total));
        return response()->json($this->transformQuote($recurring_quote));
    }

    /**
     * @param int $id
     * @param StoreRecurringQuoteRequest $request
     * @return mixed
     */
    public function update(int $id, StoreRecurringQuoteRequest $request)
    {
        $recurring_quote = $this->recurring_quote_repo->findQuoteById($id);

        $recurring_quote = $this->recurring_quote_repo->save($request->all(), $recurring_quote);
        return response()->json($this->transformQuote($recurring_quote));
    }


    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');
        $recurring_quotes = RecurringQuote::withTrashed()->find($ids);

        return response()->json($recurring_quotes);

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $invoice = $this->recurring_quote_repo->findQuoteById($id);
        $this->recurring_quote_repo->archive($invoice);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $quote = RecurringQuote::withTrashed()->where('id', '=', $id)->first();
        $this->recurring_quote_repo->newDelete($quote);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = RecurringQuote::withTrashed()->where('id', '=', $id)->first();
        $this->recurring_quote_repo->restore($group);
        return response()->json([], 200);
    }

}
