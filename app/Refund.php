<?php


namespace App;


use App\Factory\CreditFactory;
use App\Factory\NotificationFactory;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Repositories\CreditRepository;

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
        if (!empty($this->data['invoices'])) {
            return $this->refundPaymentWithInvoices();
        }

        return $this->refundPaymentWithNoInvoices();
    }

    private function refundPaymentWithNoInvoices()
    {
        //adjust payment refunded column amount
        $this->payment->refunded = $this->data['amount'];

        $this->payment->status_id = $this->data['amount'] == $this->payment->amount ? Payment::STATUS_REFUNDED : Payment::STATUS_PARTIALLY_REFUNDED;

        $credit_note = CreditFactory::create($this->payment->account_id, $this->payment->user_id, $this->payment->customer);

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
                'balance'    => $this->data['amount']],
            $credit_note
        );

        return $this;
    }

    private function refundPaymentWithInvoices()
    {
        $total_refund = 0;
        $total_refund = 0;

        /* Build Credit Note*/
        $credit_note = CreditFactory::create($this->payment->account_id, $this->payment->user_id, $this->payment->customer);

        $line_items = [];
        $adjustment_amount = 0;

        foreach ($this->data['invoices'] as $invoice) {
            $inv = Invoice::find($invoice['invoice_id']);
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

        $credit_note = $this->credit_repo->save(
            [
                'line_items' => $line_items,
                'total'      => $this->payment->refunded,
                'balance'    => $this->payment->refunded
            ],
            $credit_note
        );

        $this->payment->save();

        $this->payment->customer->paid_to_date -= $this->data['amount'];
        $this->payment->customer->save();
        $credit_note->ledger()->updateBalance($adjustment_amount);

        return $this;
    }
}
