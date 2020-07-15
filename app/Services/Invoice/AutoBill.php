<?php

namespace App\Services\Invoice;

use App\Helpers\InvoiceCalculator\LineItem;
use App\Helpers\Payment\Gateways\Authorize;
use App\Helpers\Payment\Gateways\GatewayFactory;
use App\Helpers\Payment\Gateways\Stripe;
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
        if ($this->invoice->status_id === Invoice::STATUS_DRAFT) {
            $this->invoice_repo->markSent($this->invoice);
        }

        $amount = $this->invoice->partial > 0 ? $this->invoice->partial : $this->invoice->balance;
        $gateway_obj = (new GatewayFactory())->create($this->invoice->customer);
        return $gateway_obj->build($amount, $this->invoice);
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

        return $this->build();
    }
}
