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
    private PurchaseOrder $purchase_order;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param PurchaseOrder $po
     * @param CompanyContact|null $contact
     * @param bool $update
     */
    public function __construct(PurchaseOrder $purchase_order, CompanyContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->purchase_order = $purchase_order;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->purchase_order->company->primary_contact()->first();
        }

        $file_path = $this->purchase_order->getPdfFilename();
        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->purchase_order->getDesignId());
        $objPdf = new PurchaseOrderPdf($this->purchase_order);
        $designer =
            new PdfColumns(
                $objPdf,
                $this->purchase_order,
                $design,
                $this->purchase_order->account->settings->pdf_variables,
                'purchase_order'
            );

        return CreatePdf::dispatchNow($objPdf, $this->purchase_order, $file_path, $designer, $this->contact);
    }
}
