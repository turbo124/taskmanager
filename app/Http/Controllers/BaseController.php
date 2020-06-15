<?php


namespace App\Http\Controllers;


use App\Credit;
use App\Events\Misc\InvitationWasViewed;
use App\Factory\CloneCreditFactory;
use App\Factory\CloneCreditToQuoteFactory;
use App\Factory\CloneInvoiceFactory;
use App\Factory\CloneInvoiceToQuoteFactory;
use App\Factory\CloneOrderToInvoiceFactory;
use App\Factory\CloneOrderToQuoteFactory;
use App\Factory\CloneQuoteFactory;
use App\Factory\CloneQuoteToInvoiceFactory;
use App\Factory\CloneQuoteToOrderFactory;
use App\Invoice;
use App\Jobs\Pdf\Download;
use App\Order;
use App\Payment;
use App\Quote;
use App\Repositories\CreditRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\QuoteRepository;
use App\Transformations\CreditTransformable;
use App\Transformations\InvoiceTransformable;
use App\Transformations\OrderTransformable;
use App\Transformations\QuoteTransformable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class BaseController
 * @package App\Http\Controllers
 */
class BaseController extends Controller
{
    use CreditTransformable;
    use QuoteTransformable;
    use InvoiceTransformable;
    use OrderTransformable;

    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoice_repo;

    /**
     * @var QuoteRepository
     */
    private QuoteRepository $quote_repo;

    /**
     * @var CreditRepository
     */
    private CreditRepository $credit_repo;

    /**
     * @var string
     */
    private string $entity_string;

    /**
     * BaseController constructor.
     * @param InvoiceRepository $invoice_repo
     * @param QuoteRepository $quote_repo
     * @param CreditRepository $credit_repo
     * @param string $entity_string
     */
    public function __construct(
        InvoiceRepository $invoice_repo,
        QuoteRepository $quote_repo,
        CreditRepository $credit_repo,
        string $entity_string
    ) {
        $this->invoice_repo = $invoice_repo;
        $this->quote_repo = $quote_repo;
        $this->credit_repo = $credit_repo;
        $this->entity_string = $entity_string;
    }

    /**
     * @param Request $request
     * @param $entity
     * @param $action
     * @param bool $bulk
     * @return array|bool|JsonResponse|string
     * @throws FileNotFoundException
     * @throws \ReflectionException
     */
    protected function performAction(Request $request, $entity, $action, $bulk = false)
    {
        switch ($action) {
            //order
            case 'clone_order_to_invoice':
                $invoice = CloneOrderToInvoiceFactory::create(
                    $entity,
                    auth()->user(),
                    auth()->user()->account_user()->account
                );

                $this->invoice_repo->createInvoice($request->all(), $invoice);
                $response = $this->transformInvoice($invoice);
                break;
            case 'clone_order_to_quote': // done
                $quote = CloneOrderToQuoteFactory::create(
                    $entity,
                    auth()->user(),
                    auth()->user()->account_user()->account
                );
                $this->quote_repo->createQuote($request->all(), $quote);
                $response = $this->transformQuote($quote);
                break;

            case 'hold_order':
                $order = $entity->service()->holdOrder();

                if (!$order) {
                    $response = false;
                    $message = 'Order is already hold';
                } else {
                    $response = $this->transformOrder($order);
                }

                break;
            case 'fulfill':
                $order = $entity->service()->fulfillOrder((new OrderRepository(new Order)));
                $order->save();
                $response = $this->transformOrder($order);
                break;
            case 'reverse_status':
                $order = $entity->service()->reverseStatus();

                if (!$order) {
                    $response = false;
                    $message = 'Order is not on hold';
                } else {
                    $response = $this->transformEntity($order);
                }

                break;
            case 'dispatch': // done
                if (!in_array($entity->status_id, [Order::STATUS_BACKORDERED, Order::STATUS_DRAFT, Order::STATUS_SENT, Order::STATUS_COMPLETE])) {
                    $message = 'Unable to approve this order as it has expired.';
                    $response = false;
                } else {
                    $response = $entity->service()->dispatch($this->invoice_repo, (new OrderRepository(new Order)));
                    $response = $this->transformEntity($response);
                }

                break;

            //quote
            case 'clone_quote_to_invoice': // done
                $invoice = $entity->service()->convertQuoteToInvoice($this->invoice_repo);

                if (!$invoice) {
                    $response = false;
                } else {
                    $response = $this->transformInvoice($invoice);
                }

                break;
            case 'clone_to_order':
                $order = $entity->service()->convertQuoteToOrder((new OrderRepository(new Order())));

                if (!$order) {
                    $response = false;
                } else {
                    $response = $this->transformOrder($order);
                }
                break;
            case 'clone_to_quote': // done
                $quote = CloneQuoteFactory::create($entity, auth()->user());
                $this->quote_repo->createQuote($request->all(), $quote);
                $response = $this->transformQuote($quote);
                break;
            case 'mark_sent': //done
                $entity = $this->invoice_repo->markSent($entity);

                if (!$entity) {
                    $response = false;
                } else {
                    if ($this->entity_string === 'Invoice') {
                        $entity->customer->increaseBalance($entity->balance);
                        $entity->customer->save();
                        $entity->transaction_service()->createTransaction($entity->balance);
                    }

                    $response = $this->transformEntity($entity);
                }

                break;
            case 'clone_to_credit': // done
                $credit = CloneCreditFactory::create($entity, auth()->user());
                $this->credit_repo->createCreditNote($request->all(), $credit);
                $response = $this->transformCredit($credit);
                break;
            case 'clone_credit_to_quote': //done
                $quote = CloneCreditToQuoteFactory::create($entity, auth()->user());
                (new QuoteRepository(new Quote))->createQuote($request->all(), $quote);
                $response = $this->transformQuote($quote);
                break;

            case 'approve': //done
                $quote = $entity->service()->approve($this->invoice_repo, $this->quote_repo);

                if (!$quote) {
                    $message = 'Unable to approve this quote as it has expired.';
                    $response = false;
                } else {
                    $quote->save();

                    $response = $this->transformEntity($quote);
                }

                break;
            case 'download': //done
                $disk = config('filesystems.default');
                $content = Storage::disk($disk)->get($entity->service()->generatePdf(null));
                $response = ['data' => base64_encode($content)];
                break;
            case 'archive': //done
                $this->invoice_repo->archive($entity);
                $response = $this->transformEntity($entity);
                break;
            case 'delete': //done
                $this->quote_repo->newDelete($entity);
                $response = $this->transformEntity($entity);
                break;
            case 'email': //done
                $template = strtolower($this->entity_string);
                $subject = $entity->customer->getSetting('email_subject_' . $template);
                $body = $entity->customer->getSetting('email_template_' . $template);
                $entity->service()->sendEmail(null, $subject, $body);
                $response = 'email sent';
                break;
            case 'clone_to_invoice': // done
                $invoice = CloneInvoiceFactory::create(
                    $entity,
                    auth()->user(),
                    auth()->user()->account_user()->account
                );
                $this->invoice_repo->createInvoice($request->all(), $invoice);
                $response = $this->transformInvoice($invoice);
                break;
            case 'clone_invoice_to_quote': // done
                $quote = CloneInvoiceToQuoteFactory::create($entity, auth()->user());
                (new QuoteRepository(new Quote))->createQuote($request->all(), $quote);
                $response = $this->transformQuote($quote);
                break;
            case 'create_payment': // done
                $invoice = $entity->service()->createPayment($this->invoice_repo, new PaymentRepository(new Payment));

                if (!$invoice) {
                    $response = false;
                    $message = 'Unable to mark invoice as paid';
                } else {
                    $response = $this->transformInvoice($invoice);
                }

                break;
            case 'reverse': // done
                $invoice = $entity->service()->reverseInvoicePayment(
                    new CreditRepository(new Credit),
                    new PaymentRepository(new Payment)
                );

                if (!$invoice) {
                    $response = false;
                    $message = 'Unable to reverse invoice payment';
                } else {
                    $response = $this->transformInvoice($invoice);
                }

                break;

            case 'cancel': //done
                $invoice = $entity->service()->cancelInvoice();

                $response = $this->transformInvoice($invoice);

                break;
            default:
                $response = false;
                $message = "The requested action `{$action}` is not available.";
                break;
        }

        if ($bulk === true) {
            return $response;
        }

        if ($response !== false) {
            return response()->json($response);
        }

        if (isset($message)) {
            return response()->json($message);
        }

        return response()->json('The request action failed to complete');
    }

    /**
     * @param $entity
     * @return array
     */
    private function transformEntity($entity)
    {
        switch ($this->entity_string) {
            case 'Invoice':
                return $this->transformInvoice($entity);

            case 'Credit':
                return $this->transformCredit($entity);

            case 'Quote':
                return $this->transformQuote($entity);

            case 'Order':
                return $this->transformOrder($entity);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function bulk(Request $request)
    {
        $action = $request->action;

        $ids = $request->ids;

        $class = "App\\{$this->entity_string}";

        $entities = $class::withTrashed()->whereIn('id', $ids)->get();

        if (!$entities) {
            return response()->json(['message' => "No {$this->entity_string} Found"]);
        }

        if ($action == 'download' && $entities->count() >= 1) {
            Download::dispatch($entities, $entities->first()->account, auth()->user()->email);

            return response()->json(['message' => 'The email was sent successfully!'], 200);
        }

        $responses = [];

        foreach ($entities as $entity) {
            $response = $this->performAction($request, $entity, $action, true);

            if ($response === false) {
                $responses[] = "FAILED";
                continue;
            }

            $responses[] = $response;
        }

        return response()->json($responses);
    }

    public function downloadPdf()
    {
        $ids = request()->input('ids');

        $class = "App\\{$this->entity_string}";

        $entities = $class::withTrashed()->whereIn('id', $ids)->get();

        if (!$entities) {
            return response()->json(['message' => "No {$this->entity_string} Found"]);
        }

        $disk = config('filesystems.default');
        $pdfs = [];

        foreach ($entities as $entity) {
            $content = Storage::disk($disk)->get($entity->service()->generatePdf(null));
            $pdfs[$entity->number] = base64_encode($content);
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
        $invitation = $this->invoice_repo->getInvitation(['key' => $invitation_key], $this->entity_string);
        $contact = $invitation->contact;
        $entity = $invitation->{strtolower($this->entity_string)};

        $disk = config('filesystems.default');
        $content = Storage::disk($disk)->get($entity->service()->generatePdf($contact));

        if (request()->has('markRead') && request()->input('markRead') === 'true') {
            $invitation->markViewed();
            event(new InvitationWasViewed(strtolower($this->entity_string), $invitation));
        }

        return response()->json(['data' => base64_encode($content)]);
    }
}
