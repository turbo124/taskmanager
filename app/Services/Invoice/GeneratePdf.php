<?php

namespace App\Services\Invoice;

use App\Models\ClientContact;
use App\Models\Design;
use App\Designs\PdfColumns;
use App\Helpers\Pdf\InvoicePdf;
use App\Models\Invoice;
use App\Jobs\Pdf\CreatePdf;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    private $contact;

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param \App\Models\Invoice $invoice
     * @param ClientContact|null $contact
     * @param bool $update
     */
    public function __construct(Invoice $invoice, ClientContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->invoice = $invoice;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->invoice->customer->primary_contact()->first();
        }

        $file_path = $this->invoice->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->invoice->getDesignId());

        $objPdf = new InvoicePdf($this->invoice);

        $designer =
            new PdfColumns(
                $objPdf, $this->invoice, $design, $this->invoice->account->settings->pdf_variables, 'invoice'
            );

        return CreatePdf::dispatchNow($objPdf, $this->invoice, $file_path, $designer, $this->contact);
    }

}
