<?php

namespace App\Services\Lead;

use App\Designs\PdfColumns;
use App\Helpers\Pdf\LeadPdf;
use App\Jobs\Pdf\CreatePdf;
use App\Models\ClientContact;
use App\Models\Deal;
use App\Models\Design;
use App\Models\Lead;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    private $contact;

    /**
     * @var Deal
     */
    private Lead $lead;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param Lead $lead
     * @param ClientContact|null $contact
     * @param bool $update
     */
    public function __construct(Lead $lead, ClientContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->lead = $lead;
        $this->update = $update;
    }

    public function execute()
    {
        $file_path = $this->lead->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->lead->getDesignId());

        $objPdf = new LeadPdf($this->lead);

        $designer =
            new PdfColumns(
                $objPdf, $this->lead, $design, $this->lead->account->settings->pdf_variables, 'lead'
            );

        return CreatePdf::dispatchNow($objPdf, $this->lead, $file_path, $designer, $this->contact);
    }

}
