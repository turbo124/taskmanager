<?php

namespace App;

use App\Events\Payment\PaymentWasRefunded;
use App\Events\Credit\CreditWasCreated;
use App\Factory\CreditFactory;
use App\Factory\NotificationFactory;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Repositories\CreditRepository;
use Omnipay\Omnipay;

class Refund
{

    private $payment;

    private $credit_repo;

    private $data;

    public function __construct(Payment $payment, CreditRepository $credit_repo, array $data)
    {
        $this->payment = $payment;
        $this->credit_repo = $credit_repo;
        $this->data = $data;
    }

    public function refund()
    {
        if (!empty($this->payment->company_gateway_id)) {
            if (!$this->gatewayRefund()) {
                return false;
            }
        }

        if (!empty($this->data['invoices'])) {
            return $this->refundPaymentWithInvoices();
        }

        return $this->refundPaymentWithNoInvoices();
    }

    private function refundPaymentWithNoInvoices()
    {
        //adjust payment refunded column amount
        $this->payment->refunded += $this->data['amount'];

        $this->payment->status_id = $this->data['amount'] == $this->payment->amount ? Payment::STATUS_REFUNDED : Payment::STATUS_PARTIALLY_REFUNDED;

        $credit_note = CreditFactory::create($this->payment->account, $this->payment->user, $this->payment->customer);

        $line_items[] = (new LineItem($credit_note))
            ->setQuantity(1)
            ->setUnitPrice($this->data['amount'])
            ->setProductId('CREDIT')
            ->setNotes('REFUND for transaction_reference ' . $this->payment->number)
            ->setSubTotal($this->data['amount'])
            ->toObject();

        $credit_note = $this->credit_repo->save(
            [
                'line_items' => $line_items,
                'total'      => $this->data['amount'],
                'balance'    => $this->data['amount']
            ],
            $credit_note
        );

        event(new CreditWasCreated($credit_note));

        $this->payment->save();
        event(new PaymentWasRefunded($this->payment, $this->data['amount']));

        return $this->payment;
    }

    private function refundPaymentWithInvoices()
    {
        $total_refund = 0;
        $total_refund = 0;

        $line_items = [];
        $adjustment_amount = 0;

        $ids = array_column($this->data['invoices'], 'invoice_id');
        $invoices = Invoice::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($this->data['invoices'] as $invoice) {
            if (!isset($invoices[$invoice['invoice_id']])) {
                continue;
            }

            $inv = $invoices[$invoice['invoice_id']];

            $line_items[] = (new LineItem($inv))
                ->setQuantity(1)
                ->setUnitPrice($invoice['amount'])
                ->setProductId('CREDIT')
                ->setNotes('REFUND for invoice number ' . $inv->number)
                ->setSubTotal($invoice['amount'])
                ->toObject();

            $adjustment_amount += $invoice['amount'];

            $inv->adjustInvoices($invoice['amount']);

            $this->payment->refunded += $invoice['amount'];
        }

        $this->payment->status_id = $this->payment->refunded == $this->payment->amount ? Payment::STATUS_REFUNDED : Payment::STATUS_PARTIALLY_REFUNDED;

        $this->createCreditNote($line_items, $adjustment_amount);

        $this->payment->save();

        event(new PaymentWasRefunded($this->payment, $adjustment_amount));

        $this->updateCustomer();


        return $this->payment;
    }

    private function gatewayRefund()
    {
        if (empty($this->payment->company_gateway_id)) {
            return false;
        }

        $company_gateway = CompanyGateway::find($this->payment->company_gateway_id);

        if (!$company_gateway) {
            return false;
        }

        $gateway = Omnipay::create($company_gateway->gateway->provider);

        $gateway->initialize((array)$company_gateway->config);

        $response = $gateway
            ->refund(
                [
                    'transactionReference' => $this->payment->transaction_reference,
                    'amount'               => $this->data['amount'] ?? $this->payment->amount,
                    'currency'             => $this->payment->customer->currency->code
                ]
            )
            ->send();

        if ($response->isSuccessful()) {
            return true;
        }

        return false;
    }

    private function updateCustomer()
    {
        $this->payment->customer->paid_to_date -= $this->data['amount'];
        $this->payment->customer->save();
        return $this->payment->customer;
    }

    /**
     * @param $line_items
     * @param $adjustment_amount
     * @return Credit|null
     */
    private function createCreditNote($line_items, $adjustment_amount)
    {
        $credit_note = CreditFactory::create($this->payment->account, $this->payment->user, $this->payment->customer);

        $credit_note = $this->credit_repo->save(
            [
                'line_items' => $line_items,
                'total'      => $this->payment->refunded,
                'balance'    => $this->payment->refunded
            ],
            $credit_note
        );

        event(new CreditWasCreated($credit_note));

        $credit_note->ledger()->updateBalance($adjustment_amount);

        return $credit_note;
    }
}
