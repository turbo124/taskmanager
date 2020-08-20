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

    private function build(Invoice $invoice)
    {
        $invoice_repo = new InvoiceRepository($invoice);

        if ($invoice->status_id === Invoice::STATUS_DRAFT) {
            $invoice_repo->markSent($invoice);
        }

        $this->addFeeToInvoice($invoice_repo, $invoice);

        $amount = $invoice->partial > 0 ? $invoice->partial : $invoice->balance;
        $gateway_obj = (new GatewayFactory())->create($invoice->customer);
        return $gateway_obj->build($amount, $invoice);
    }

    private function addFeeToInvoice (InvoiceRepository $invoice_repo, Invoice $invoice) {
        $fee = $this->findGatewayFee();

        if(empty($fee)) {
            return true;
        }

        $invoice_repo->save(['gateway_fee' => $fee], $invoice);
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

    public function execute(Invoice $invoice)
    {
        if ($invoice->is_deleted || !in_array(
                $invoice->status_id,
                [
                    Invoice::STATUS_SENT,
                    Invoice::STATUS_PARTIAL,
                    Invoice::STATUS_DRAFT
                ]
            )) {
            return null;
        }

        return $this->build($invoice);
    }






}
