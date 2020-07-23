<?php

namespace App\Http\Controllers;

use App\Helpers\Pdf\InvoicePdf;
use App\Helpers\Pdf\LeadPdf;
use App\Traits\MakesInvoiceHtml;
use App\Utils\TemplateEngine;


class TemplateController extends Controller
{
    use MakesInvoiceHtml;

    public function __construct()
    {
    }

    /**
     * Returns a template filled with entity variables
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function show()
    {
        // if no entity provided default to invoice
        $entity = request()->has('entity') ? request()->input('entity') : 'Invoice';
        $entity_id = request()->has('entity_id') ? request()->input('entity_id') : '';
        $subject = request()->has('subject') ? request()->input('subject') : '';
        $body = request()->has('body') ? request()->input('body') : '';
        $template = request()->has('template') ? request()->input('template') : '';
        $class = 'App\\' . ucfirst($entity);

        $entity_object = !$entity_id ? $class::first() : $class::whereId($entity_id)->first();

        $objPdfBuilder = $class === 'App\Models\Lead' ? new LeadPdf($entity_object) : new InvoicePdf($entity_object);

        $data = (new TemplateEngine(
            $objPdfBuilder, $body, $subject, $entity, $entity_id, $template
        ))->build();

        return response()->json($data, 200);
    }
}
