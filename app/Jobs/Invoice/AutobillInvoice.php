<?php

namespace App\Jobs\Invoice;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Helpers\Payment\Gateways\Authorize;
use App\Helpers\Payment\Gateways\GatewayFactory;
use App\Helpers\Payment\Gateways\Stripe;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;

class AutobillInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Invoice $invoice;

    private InvoiceRepository $invoice_repo;

    public function __construct(Invoice $invoice, InvoiceRepository $invoice_repo)
    {
        $this->invoice = $invoice;
        $this->invoice_repo = $invoice_repo;
    }

    public function handle()
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

    private function build(Invoice $invoice)
    {
        if ($this->invoice->status_id === Invoice::STATUS_DRAFT) {
            $this->invoice_repo->markSent();
        }

        $this->addFeeToInvoice();

        $amount = $this->invoice->partial > 0 ? $this->invoice->partial : $this->invoice->balance;
        $gateway_obj = (new GatewayFactory())->create($this->invoice->customer);
        return $gateway_obj->build($amount);
    }

    private function addFeeToInvoice () {
        $fee = $this->findGatewayFee();

        if(empty($fee)) {
            return true;
        }

        $this->invoice_repo->save(['gateway_fee' => $fee]);
    }

    private function findGatewayFee ($amount) { 
       //TODO
       $gateways = CompanyGateway::where('is_default', '=', 1)->first();

       foreach($gateways as $gateway) {
           if(!empty($gateway['min_limit'] && $amount < $gateway['min_limit']) {
               continue;
           }

           if(!empty($gateway['max_limit'] && $amount > $gateway['max_limit']) {
               continue;
           }

           return $gateway;
       }
       
       return false;
    }
}
