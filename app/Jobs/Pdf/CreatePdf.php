<?php

namespace App\Jobs\Pdf;

use App\Designs\Custom;
use App\Designs\Clean;
use App\Designs\PdfColumns;
use App\Design;
use App\Account;
use App\PdfData;
use App\Traits\MakesInvoiceHtml;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\ClientContact;

class CreatePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MakesInvoiceHtml;

    public $entity;

    private $disk;

    private $contact;

    private $file_path;

    private $designer;

    private $objPdf;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PdfData $objPdf, $entity, $file_path, $designer, $contact = null, $disk = 'public')
    {
        $this->entity = $entity;
        $this->objPdf = $objPdf;
        $this->contact = $contact;
        $this->designer = $designer;
        $this->file_path = $file_path;
        $this->disk = $disk ?? config('filesystems.default');
    }

    public function handle()
    {
        if (!empty($this->contact)) {
            App::setLocale($this->contact->preferredLocale());
        }

        //get invoice design
        $html = $this->generateEntityHtml($this->objPdf, $this->designer, $this->entity, $this->contact);

        //todo - move this to the client creation stage so we don't keep hitting this unnecessarily
        Storage::makeDirectory(dirname($this->file_path), 0755);

        //\Log::error($html);
        $pdf = $this->makePdf(null, null, $html);

        Storage::disk($this->disk)->put($this->file_path, $pdf);

        return $this->file_path;
    }

    private function makePdf($header, $footer, $html)
    {
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
