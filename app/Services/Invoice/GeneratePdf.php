<?php

namespace App\Services\Invoice;

use App\Designs\PdfColumns;
use App\Helpers\Pdf\InvoicePdf;
use App\Jobs\Pdf\CreatePdf;
use App\Models\CustomerContact;
use App\Models\Design;
use App\Models\Invoice;
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
     * @param Invoice $invoice
     * @param CustomerContact|null $contact
     * @param bool $update
     */
    public function __construct(Invoice $invoice, CustomerContact $contact = null, $update = false)
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
