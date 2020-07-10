<?php

namespace App\Services\Invoice;

use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;

class AutoBill
{
    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoice_repo;

    /**
     * AutoBill constructor.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice, $invoice_repo)
    {
        $this->invoice = $invoice;
        $this->invoice_repo = $invoice_repo;
    }

    private function build()
    {
        $fee = $this->invoice->gateway_fee / $this->invoice->total;
        $fee_remaining = $fee * $this->invoice->balance;
        $this->addCharge($fee_remaining);
        $this->save();
        return true;
    }

    private function addCharge(float $amount)
    {
        // update total
        $this->invoice->total += $amount;

        // create line
        $line_items = array_filter(
            $this->invoice->line_items,
            function ($item) {
                return ($item->type_id !== 2);
            }
        );

        $line_items[] = (new LineItem)
            ->setQuantity(1)
            ->setNotes('Autobill invoice')
            ->setUnitPrice($amount)
            ->setSubTotal($amount)
            ->setTypeId(2)
            ->toObject();

        $this->invoice->line_items = $line_items;
    }

    private function save()
    {
        $this->invoice_repo->save([], $this->invoice);
    }

    public function execute()
    {
        if ($this->invoice->is_deleted || !in_array(
                $this->invoice->status_id,
                [
                    Invoice::STATUS_SENT,
                    Invoice::STATUS_PARTIAL,
                    Invoice::STATUS_DRAFT
                ]
            )) {
            return null;
        }

        $this->build();

        return $this->invoice;
    }
}
