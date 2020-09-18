<?php

namespace App\Services\Task;

use App\Designs\PdfColumns;
use App\Helpers\Pdf\TaskPdf;
use App\Jobs\Pdf\CreatePdf;
use App\Models\Cases;
use App\Models\CustomerContact;
use App\Models\Deal;
use App\Models\Design;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    private $contact;

    /**
     * @var Cases|Deal
     */
    private Task $task;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param Task $task
     * @param CustomerContact|null $contact
     * @param bool $update
     */
    public function __construct(Task $task, CustomerContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->task = $task;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->task->customer->primary_contact()->first();
        }

        $file_path = $this->task->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->task->getDesignId());

        $objPdf = new TaskPdf($this->task);

        $designer =
            new PdfColumns(
                $objPdf, $this->task, $design, $this->task->account->settings->pdf_variables, 'task'
            );

        return CreatePdf::dispatchNow($objPdf, $this->task, $file_path, $designer, $this->contact);
    }

}
