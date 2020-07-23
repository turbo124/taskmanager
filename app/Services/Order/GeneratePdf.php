<?php

namespace App\Services\Order;

use App\Models\ClientContact;
use App\Models\Design;
use App\Designs\PdfColumns;
use App\Helpers\Pdf\InvoicePdf;
use App\Jobs\Pdf\CreatePdf;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    private $contact;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * @var bool
     */
    private bool $update;

    /**
     * GeneratePdf constructor.
     * @param \App\Models\Order $order
     * @param ClientContact|null $contact
     * @param bool $update
     */
    public function __construct(Order $order, ClientContact $contact = null, $update = false)
    {
        $this->contact = $contact;
        $this->order = $order;
        $this->update = $update;
    }

    public function execute()
    {
        if (!$this->contact) {
            $this->contact = $this->order->customer->primary_contact()->first();
        }

        $file_path = $this->order->getPdfFilename();

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if ($file && $this->update === false) {
            return $file_path;
        }

        $design = Design::find($this->order->getDesignId());

        $objPdf = new InvoicePdf($this->order);

        $designer =
            new PdfColumns(
                $objPdf, $this->order, $design, $this->order->account->settings->pdf_variables, 'order'
            );

        return CreatePdf::dispatchNow($objPdf, $this->order, $file_path, $designer, $this->contact);
    }

}
