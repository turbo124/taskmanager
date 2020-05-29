<?php

namespace App\Services\Credit;

use App\ClientContact;
use App\Credit;
use App\Design;
use App\Designs\PdfColumns;
use App\Jobs\Pdf\CreatePdf;
use App\PdfData;
use Illuminate\Support\Facades\Storage;

class GetPdf
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
     * GetPdf constructor.
     * @param Credit $credit
     * @param ClientContact|null $contact
     * @param bool $update
     */
    public function __construct(Credit $credit, ClientContact $contact = null, $update = false)
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

        $objPdf = new PdfData($this->credit);

        $designer =
            new PdfColumns(
                $objPdf, $this->credit, $design, $this->credit->account->settings->pdf_variables, 'credit'
            );

        return CreatePdf::dispatchNow($objPdf, $this->credit, $file_path, $designer, $this->contact);
    }
}
