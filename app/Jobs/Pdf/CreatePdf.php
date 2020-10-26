<?php

namespace App\Jobs\Pdf;

use App\Traits\MakesInvoiceHtml;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class CreatePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MakesInvoiceHtml;

    public $entity;

    private $disk;

    private $contact;

    private $file_path;

    private $designer;

    private $objPdf;
    
    private $update = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($objPdf, $entity, $contact = null, $disk = 'public', $update = false)
    {
        $this->entity = $entity;
        $this->objPdf = $objPdf;
        $this->contact = $contact;
        $this->disk = $disk ?? config('filesystems.default');
        $this->update = $update;
    }

    public function handle()
    {
        if (!empty($this->contact)) {
            App::setLocale($this->contact->preferredLocale());
        }
        
        $this->file_path = $this->entity->getPdfFilename();
        
        if($this->checkIfExists()) {
            return $this->file_path;
        }
        
        $design = Design::find($this->entity->getDesignId());
        
        $entity = strtolower((new \ReflectionClass($this->entity))->getShortName());

        $this->designer =
            new PdfColumns(
                $this->objPdf, $this->entity, $design, $this->entity->account->settings->pdf_variables, $entity
            );   

        $this->build();
       

        return $this->file_path;
    }
    
    private function checkIfExists()
    {
        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($this->file_path);

        if ($file && $this->update === false) {
            return true;
        }

        return false;
    }
    
    private function build()
    {
         //get invoice design
        $html = $this->generateEntityHtml($this->objPdf, $this->designer, $this->entity, $this->contact);

        //todo - move this to the client creation stage so we don't keep hitting this unnecessarily
        Storage::makeDirectory(dirname($this->file_path), 0755);

        //\Log::error($html);
        $pdf = $this->makePdf(null, null, $html);

        Storage::disk($this->disk)->put($this->file_path, $pdf);
    }

    private function makePdf($header, $footer, $html)
    {
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
