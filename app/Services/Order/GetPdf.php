<?php

namespace App\Services\Order;

use App\ClientContact;
use App\Design;
use App\Designs\Designer;
use App\Jobs\Pdf\CreatePdf;
use App\Order;
use App\PdfData;
use Illuminate\Support\Facades\Storage;

class GetPdf
{
    private $contact;
    private $order;

    public function __construct(Order $order, ClientContact $contact = null)
    {
        $this->contact = $contact;
        $this->order = $order;
    }

    public function run()
    {
        if (!$this->contact) {
            $this->contact = $this->order->customer->primary_contact()->first();
        }

        $path = 'storage/' . $this->order->account->id . '/' . $this->order->customer->id . '/orders/';
        $file_path = $path . $this->order->number . '.pdf';

        $design = Design::find($this->order->account->settings->order_design_id);
        $objPdf = new PdfData($this->order);
        $designer =
            new Designer($objPdf, $this->order, $design, $this->order->account->settings->pdf_variables, 'order');

        $disk = config('filesystems.default');
        $file = Storage::disk($disk)->exists($file_path);

        if (!$file) {
            $file_path = CreatePdf::dispatchNow($objPdf, $this->order, $file_path, $designer, $this->contact);
        }

        return $file_path;
    }

}
