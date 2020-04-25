<?php

namespace App\Http\Controllers;

use App\Events\Credit\CreditWasCreated;
use App\Events\Misc\InvitationWasViewed;
use App\Factory\CloneCreditFactory;
use App\Factory\CloneCreditToQuoteFactory;
use App\Filters\CreditFilter;
use App\Jobs\Credit\EmailCredit;
use App\Jobs\Invoice\MarkInvoicePaid;
use App\Quote;
use App\Repositories\QuoteRepository;
use App\Requests\Credit\ActionCreditRequest;
use App\Requests\Credit\CreateCreditRequest;
use App\Requests\Credit\UpdateCreditRequest;
use App\Customer;
use App\Credit;
use App\Repositories\CreditRepository;
use App\Services\CreditService;
use App\Repositories\Interfaces\CreditRepositoryInterface;
use App\Requests\SearchRequest;
use App\Transformations\CreditTransformable;
use App\Factory\CreditFactory;
use App\Transformations\QuoteTransformable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CreditController extends Controller
{
    use CreditTransformable;
    use QuoteTransformable;

    protected $credit_repo;

    /**
     * CreditController constructor.
     * @param CreditRepositoryInterface $credit_repo
     */
    public function __construct(CreditRepositoryInterface $credit_repo)
    {
        $this->credit_repo = $credit_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SearchRequest $request)
    {
        $credits = (new CreditFilter($this->credit_repo))->filter($request, auth()->user()->account_user()->account_id);

        return response()->json($credits);
    }

    /**
     * @param UpdateCreditRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UpdateCreditRequest $request, int $id)
    {

        $credit = $this->credit_repo->findCreditById($id);

        $credit = $this->credit_repo->save($request->all(), $credit);
        return response()->json($this->transformCredit($credit));
    }

    /**
     * @param CreateCreditRequest $request
     * @return mixed
     */
    public function store(CreateCreditRequest $request)
    {
        $customer = Customer::find($request->input('customer_id'));
        $credit = $this->credit_repo->save($request->all(),
            CreditFactory::create(auth()->user()->account_user()->account_id, auth()->user()->id, $customer));
        event(new CreditWasCreated($credit, $credit->account));
        return response()->json($this->transformCredit($credit));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function archive(int $id)
    {
        $invoice = $this->credit_repo->findCreditById($id);
        $this->credit_repo->archive($invoice);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $credit = Credit::withTrashed()->where('id', '=', $id)->first();
        $this->credit_repo->newDelete($credit);
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = Credit::withTrashed()->where('id', '=', $id)->first();
        $this->credit_repo->restore($group);
        return response()->json([], 200);
    }

    public function show(int $id)
    {
        $credit = $this->credit_repo->findCreditById($id);
        return response()->json($this->transformCredit($credit));
    }

    public function bulk()
    {
        $action = request()->input('action');

        $ids = request()->input('ids');

        $credits = Credit::withTrashed()->whereIn('id', $ids)->get();

        if (!$credits) {
            return response()->json(['message' => 'No Credits Found']);
        }

        $credits->each(function ($credit, $key) use ($action) {
            $this->performAction($credit, request(), $action, true);
        });

        return response()->json(Credit::withTrashed()->whereIn('id', $ids));
    }

    /**
     * @param Request $request
     * @param Credit $credit
     * @param $action
     * @return mixed
     */
    public function action(Request $request, Credit $credit, $action)
    {
        return $this->performAction($credit, $request, $action);
    }

    /**
     * @param Credit $credit
     * @param $action
     * @param bool $bulk
     * @return mixed
     */
    private function performAction(Credit $credit, Request $request, $action, $bulk = false)
    {
        /*If we are using bulk actions, we don't want to return anything */
        switch ($action) {
            case 'clone_to_credit':
                $credit = CloneCreditFactory::create($credit, auth()->user()->id);
                $this->credit_repo->save($request->all(), $credit);
                return response()->json($this->transformCredit($credit));
                break;
            case 'clone_to_quote':
                $quote = CloneCreditToQuoteFactory::create($credit, auth()->user()->id);
                (new QuoteRepository(new Quote))->save($request->all(), $quote);
                return response()->json($this->transformQuote($quote));
                break;
            case 'mark_sent':
                $this->credit_repo->markSent($credit);

                if (!$bulk) {
                    return response()->json($this->transformCredit($credit));
                }
                break;
            case 'download':
                $disk = config('filesystems.default');
                $content = Storage::disk($disk)->get($credit->service()->getPdf(null));
                return response()->json(['data' => base64_encode($content)]);
                break;
            case 'archive':
                $this->credit_repo->archive($credit);

                if (!$bulk) {
                    return $this->listResponse($credit);
                }
                break;
            case 'delete':
                $this->credit_repo->delete($credit);

                if (!$bulk) {
                    return $this->listResponse($credit);
                }
                break;
            case 'email':
                $credit->service()->sendEmail();
                return response()->json(['message' => 'email sent'], 200);
                break;

            default:
                return response()->json(['message' => "The requested action `{$action}` is not available."], 400);
                break;
        }
    }

    public function downloadPdf()
    {
        $ids = request()->input('ids');

        $credits = Credit::withTrashed()->whereIn('id', $ids)->get();

        if (!$credits) {
            return response()->json(['message' => 'No Credits Found']);
        }

        $disk = config('filesystems.default');
        $pdfs = [];

        foreach ($credits as $credit) {
            $content = Storage::disk($disk)->get($credit->service()->getPdf(null));
            $pdfs[$credit->number] = base64_encode($content);
        }

        return response()->json(['data' => $pdfs]);
    }

    /**
     * @param $invitation_key
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function markViewed($invitation_key)
    {
        $invitation = $this->credit_repo->getInvitationByKey($invitation_key);
        $contact = $invitation->contact;
        $credit = $invitation->credit;

        $disk = config('filesystems.default');
        $content = Storage::disk($disk)->get($credit->service()->getPdf($contact));

        if (request()->has('markRead') && request()->input('markRead') === 'true') {
            $invitation->markViewed();
            event(new InvitationWasViewed('credit', $invitation));
        }

        return response()->json(['data' => base64_encode($content)]);
    }
}
