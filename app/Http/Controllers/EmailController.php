<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Jobs\Email\SendEmail;
use App\Lead;
use App\Invoice;
use App\Order;
use App\Quote;
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

        $contact = $entity !== 'App\\Lead' ? $entity_obj->invitations->first()->contact : null;

        $entity_obj->service()->sendEmail($contact, $request->subject, $request->body);

        if ($request->mark_sent === true) {
            $entity_obj->service()->markSent()->save();
        }

        $transformed_obj = $this->transformObject($entity_obj);

        if (!$transformed_obj) {
            return response()->json(['message' => 'Unable to transform entity'], 404);
        }

        return response()->json($transformed_obj);
    }

    private function transformObject($entity_object)
    {
        $entity_class = (new \ReflectionClass($entity_object))->getShortName();

        switch ($entity_class) {
            case 'Lead':
                return $this->transformLead($entity_object);
            case 'Credit':
                return $this->transformCredit($entity_object);
            case 'Order':
                return $this->transformOrder($entity_object);
            case 'Quote':
                return $this->transformQuote($entity_object);
            case 'Invoice':
                return $this->transformInvoice($entity_object);
        }

        return false;
    }
}
