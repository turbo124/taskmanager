<?php

namespace App\Services\Invoice;

use App\ClientContact;
use App\Design;
use App\Designs\PdfColumns;
use App\Invoice;
use App\Jobs\Pdf\CreatePdf;
use App\PdfData;
use Illuminate\Support\Facades\Storage;

class GetPdf
{
    private $contact;
    private $invoice;

    public function __construct(Invoice $invoice, ClientContact $contact = null)
    {
        $this->contact = $contact;
        $this->invoice = $invoice;
    }

    public function run()
    {
        if (!$this->contact) {
            $this->contact = $this->invoice->customer->primary_contact()->first();
        }

        $file_path = $this->invoice->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file) {
            return $file_path;
        }

        $design = Design::find($this->invoice->getDesignId());

        $objPdf = new PdfData($this->invoice);

        $designer =
            new PdfColumns(
                $objPdf, $this->invoice, $design, $this->invoice->account->settings->pdf_variables, 'invoice'
            );

        return CreatePdf::dispatchNow($objPdf, $this->invoice, $file_path, $designer, $this->contact);
    }

}
