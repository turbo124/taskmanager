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
    private $credit;

    public function __construct(Credit $credit, ClientContact $contact = null)
    {
        $this->contact = $contact;
        $this->credit = $credit;
    }

    public function run()
    {
        if (!$this->contact) {
            $this->contact = $this->credit->customer->primary_contact()->first();
        }

        $file_path = $this->credit->getPdfFilename();

        $design = Design::find($this->credit->getDesignId());

        $objPdf = new PdfData($this->credit);

        $designer =
            new PdfColumns($objPdf, $this->credit, $design, $this->credit->account->settings->pdf_variables, 'credit');

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);


        if (!$file) {
            $file_path = CreatePdf::dispatchNow($objPdf, $this->credit, $file_path, $designer, $this->contact);
        }

        return $file_path;
    }
}
