<?php

namespace App\Services\Invoice;

use App\Helpers\InvoiceCalculator\LineItem;
use App\Helpers\Payment\Gateways\Authorize;
use App\Helpers\Payment\Gateways\GatewayFactory;
use App\Helpers\Payment\Gateways\Stripe;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;

class AutoBill
{
    /**
     * @var \App\Models\Invoice
     */
    private Invoice $invoice;

    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoice_repo;

    /**
     * AutoBill constructor.
     * @param \App\Models\Invoice $invoice
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

        $this->addFeeToInvoice();

        $amount = $this->invoice->partial > 0 ? $this->invoice->partial : $this->invoice->balance;
        $gateway_obj = (new GatewayFactory())->create($this->invoice->customer);
        return $gateway_obj->build($amount, $this->invoice);
    }

    private function addFeeToInvoice () {
        $fee = $this->findGatewayFee();

        if(empty($fee)) {
            return true;
        }

        $this->invoice_repo->save(['gateway_fee' => $fee], $this->invoice);
    }

    private function findGatewayFee () { 
       //TODO
       return 5;
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
