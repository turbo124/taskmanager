<?php

namespace App\Services\Lead;

use App\ClientContact;
use App\Design;
use App\Designs\PdfColumns;
use App\Jobs\Pdf\CreatePdf;
use App\Lead;
use App\Order;
use Illuminate\Support\Facades\Storage;

class GetPdf
{
    private $contact;
    private $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function run()
    {
        $path = 'storage/' . $this->lead->account->id . '/' . $this->lead->id . '/leads/';
        $file_path = $path . $this->lead->id . '.pdf';

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file) {
            return $file_path;
        }

        $design = Design::find($this->lead->account->getSetting('invoice_design_id'));

        $designer =
            new PdfColumns($this->lead, $design, $this->lead->account->getSetting('pdf_variables'), 'lead');

        return CreatePdf::dispatchNow($this->lead, $file_path, $designer, $this->lead);
    }

}
