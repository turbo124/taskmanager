<?php

namespace App\Services\Quote;

use App\ClientContact;
use App\Design;
use App\Designs\Designer;
use App\Jobs\Pdf\CreatePdf;
use App\PdfData;
use App\Quote;
use Illuminate\Support\Facades\Storage;

class GetPdf
{
    private $contact;
    private $quote;

    public function __construct(Quote $quote, ClientContact $contact = null)
    {
        $this->contact = $contact;
        $this->quote = $quote;
    }

    public function run()
    {
        if (!$this->contact) {
            $this->contact = $this->quote->customer->primary_contact()->first();
        }

        $path = 'storage/' . $this->quote->account->id . '/' . $this->quote->customer->id . '/quotes/';
        $file_path = $path . $this->quote->number . '.pdf';

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file) {
            return $file_path;
        }

        $design = Design::find($this->quote->account->settings->quote_design_id);
        $objPdf = new PdfData($this->quote);
        $designer =
            new Designer($objPdf, $this->quote, $design, $this->quote->customer->getSetting('pdf_variables'), 'quote');

        return CreatePdf::dispatchNow($objPdf, $this->quote, $file_path, $designer, $this->contact);
    }
}
