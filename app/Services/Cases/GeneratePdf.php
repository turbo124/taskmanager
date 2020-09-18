<?php

namespace App\Services\Cases;

use App\Designs\PdfColumns;
use App\Helpers\Pdf\TaskPdf;
use App\Jobs\Pdf\CreatePdf;
use App\Models\Cases;
use App\Models\CustomerContact;
use App\Models\Deal;
use App\Models\Design;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    private $contact;

    /**
     * @var Cases|Deal
     */
    private Cases $case;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param Cases $case
     * @param CustomerContact|null $contact
     * @param bool $update
     */
    public function __construct(Cases $case, CustomerContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->case = $case;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->case->customer->primary_contact()->first();
        }

        $file_path = $this->case->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->case->getDesignId());

        $objPdf = new TaskPdf($this->case);

        $designer =
            new PdfColumns(
                $objPdf, $this->case, $design, $this->case->account->settings->pdf_variables, 'case'
            );

        return CreatePdf::dispatchNow($objPdf, $this->case, $file_path, $designer, $this->contact);
    }

}
