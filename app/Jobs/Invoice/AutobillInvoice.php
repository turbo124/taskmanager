<?php

namespace App\Jobs\Invoice;

use App\Components\Payment\Gateways\GatewayFactory;
use App\Models\CompanyGateway;
use App\Models\CustomerGateway;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use App\Traits\CreditPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutobillInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CreditPayment;

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

    private function build()
    {
        if ($this->invoice->balance <= 0 && $this->invoice->partial <= 0) {
            $credits = $this->getCreditNotesForPayment();
        }

        $balance = 0;
        $credit_total = !empty($credits) ? array_sum(array_column($credits, 'amount')) : 0;
        $amount = ($this->invoice->partial > 0) ? $this->invoice->partial : (($this->invoice->balance > 0) ? $this->invoice->balance : $credit_total);

        $customer_gateway = $this->findGatewayFee();

        if (empty($customer_gateway)) {
            return false;
        }

        $company_gateway = $customer_gateway->company_gateway;

        $amount = $this->calculateFee($company_gateway, $amount);

        $gateway_obj = (new GatewayFactory($customer_gateway, $company_gateway))->create($this->invoice->customer);
        return $gateway_obj->build($amount, $this->invoice);
    }

    private function getCreditNotesForPayment()
    {
        $credits = $this->invoice->customer->getActiveCredits();
        $credits_to_process = $this->buildCreditsToProcess($credits, $this->invoice);

        $invoices = $this->getProcessedInvoice();

        if (!empty($invoices[$this->invoice->id])) {
            $this->invoice->fill($invoices[$this->invoice->id]);
        }

        $this->invoice->temp_data = ['credits_to_process' => $credits_to_process];
        $this->invoice->save();

        return $credits_to_process;
    }

    private function findGatewayFee(): ?CustomerGateway
    {
        //TODO
        $gateways = $this->invoice->customer->gateways()->orderBy('is_default', 'DESC')->get();
        $amount = $this->invoice->total;

        foreach ($gateways as $gateway) {
            $company_gateway = $gateway->company_gateway;

            if (empty($company_gateway->fees_and_limits)) {
                continue;
            }

            $fees_and_limits = $company_gateway->fees_and_limits[0];

            if ((!empty($fees_and_limits->min_limit) && $amount < $fees_and_limits->min_limit) || (!empty($fees_and_limits->max_limit) && $amount > $fees_and_limits->max_limit)) {
                continue;
            }

            return $gateway;
        }

        return null;
    }

    private function calculateFee(CompanyGateway $company_gateway, float $amount)
    {
        $fees_and_limits = $company_gateway->fees_and_limits[0];
        $fee = 0;

        if (!empty($fees_and_limits->fee_percent) && $fees_and_limits->fee_percent > 0) {
            $fee += $amount * $fees_and_limits->fee_percent / 100;
        }

        if (!empty($fees_and_limits->fee_amount) && $fees_and_limits->fee_amount > 0) {
            $fee += $fees_and_limits->fee_amount;
        }

        if (!empty($fee)) {
            $amount += $fee;
            $this->addFeeToInvoice($fee);
        }

        return $amount;
    }

    private function addFeeToInvoice($fee)
    {
        if (empty($fee)) {
            return true;
        }

        $this->invoice_repo->save(['gateway_fee' => $fee], $this->invoice);

        return $fee;
    }
}
