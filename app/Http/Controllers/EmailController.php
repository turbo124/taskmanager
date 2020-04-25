<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Jobs\Email\SendEmail;
use App\Lead;
use App\Invoice;
use App\Notifications\SendGenericLeadNotification;
use App\Notifications\SendGenericNotification;
use App\Order;
use App\Quote;
use App\PdfData;
use App\Factory\EmailFactory;
use App\Repositories\EmailRepository;
use App\Requests\Email\SendEmailRequest;
use App\Traits\MakesInvoiceHtml;
use App\Transformations\CreditTransformable;
use App\Transformations\InvoiceTransformable;
use App\Transformations\OrderTransformable;
use App\Transformations\QuoteTransformable;
use App\Transformations\LeadTransformable;

class EmailController extends Controller
{
    use MakesInvoiceHtml;
    use CreditTransformable;
    use OrderTransformable;
    use QuoteTransformable;
    use InvoiceTransformable;
    use LeadTransformable;

    private $email_repo;

    public function __construct(EmailRepository $email_repo)
    {
        $this->email_repo = $email_repo;
    }

    /**
     * @param SendEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(SendEmailRequest $request)
    {
        $entity = ucfirst($request->input('entity'));
        $entity = "App\\$entity";

        $entity_obj = $entity::find($request->input('entity_id'));
        $entity_obj->service()->sendEmail(null, $request->subject, $request->body);

        if ($request->mark_sent === true) {
            $entity_obj->service()->markSent()->save();
        }

        if ($entity_obj instanceof Invoice) {
            return response()->json($this->transformInvoice($entity_obj));
        }

        if ($entity_obj instanceof Lead) {
            return response()->json($this->transformLead($entity_obj));
        }

        if ($entity_obj instanceof Quote) {
            return response()->json($this->transformQuote($entity_obj));
        }

        if ($entity_obj instanceof Order) {
            return response()->json($this->transformOrder($entity_obj));
        }

        if ($entity_obj instanceof Credit) {
            return response()->json($this->transformCredit($entity_obj));
        }

        if ($entity_obj instanceof Lead) {
            return response()->json($this->transformLead($entity_obj));
        }
    }
}
