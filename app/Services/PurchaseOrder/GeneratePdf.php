<?php

namespace App\Services\PurchaseOrder;

use App\Designs\PdfColumns;
use App\Helpers\Pdf\PurchaseOrderPdf;
use App\Jobs\Pdf\CreatePdf;
use App\Models\CompanyContact;
use App\Models\Design;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    private $contact;

    /**
     * @var PurchaseOrder
     */
    private PurchaseOrder $po;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param Quote $quote
     * @param ClientContact|null $contact
     * @param bool $update
     */
    public function __construct(PurchaseOrder $po, CompanyContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->po = $po;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->po->company->primary_contact()->first();
        }

        $file_path = $this->po->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->po->getDesignId());
        $objPdf = new PurchaseOrderPdf($this->po);
        $designer =
            new PdfColumns(
                $objPdf, $this->po, $design, $this->account->settings->pdf_variables, 'quote'
            );

        return CreatePdf::dispatchNow($objPdf, $this->po, $file_path, $designer, $this->contact);
    }
}
