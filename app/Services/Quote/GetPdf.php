<?php

namespace App\Services\Quote;

use App\ClientContact;
use App\Design;
use App\Designs\PdfColumns;
use App\Jobs\Pdf\CreatePdf;
use App\PdfData;
use App\Quote;
use Illuminate\Support\Facades\Storage;

class GetPdf
{
    private $contact;

    /**
     * @var Quote
     */
    private Quote $quote;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GetPdf constructor.
     * @param Quote $quote
     * @param ClientContact|null $contact
     * @param bool $update
     */
    public function __construct(Quote $quote, ClientContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->quote = $quote;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->quote->customer->primary_contact()->first();
        }

        $file_path = $this->quote->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->quote->getDesignId());
        $objPdf = new PdfData($this->quote);
        $designer =
            new PdfColumns(
                $objPdf, $this->quote, $design, $this->quote->customer->getSetting('pdf_variables'), 'quote'
            );

        return CreatePdf::dispatchNow($objPdf, $this->quote, $file_path, $designer, $this->contact);
    }
}
