<?php

namespace App\Services\Credit;

use App\Designs\PdfColumns;
use App\Helpers\Pdf\InvoicePdf;
use App\Jobs\Pdf\CreatePdf;
use App\Models\Credit;
use App\Models\CustomerContact;
use App\Models\Design;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    private $contact;

    /**
     * @var Credit
     */
    private Credit $credit;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param Credit $credit
     * @param CustomerContact|null $contact
     * @param bool $update
     */
    public function __construct(Credit $credit, CustomerContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->credit = $credit;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->credit->customer->primary_contact()->first();
        }

        $file_path = $this->credit->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->credit->getDesignId());

        $objPdf = new InvoicePdf($this->credit);

        $designer =
            new PdfColumns(
                $objPdf, $this->credit, $design, $this->credit->account->settings->pdf_variables, 'credit'
            );

        return CreatePdf::dispatchNow($objPdf, $this->credit, $file_path, $designer, $this->contact);
    }
}
