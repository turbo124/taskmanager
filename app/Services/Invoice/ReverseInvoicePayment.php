<?php

namespace App\Services\Invoice;

use App\Factory\CreditFactory;
use App\Events\Invoice\InvoiceWasReversed;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;

class ReverseInvoicePayment
{

    private $invoice;
    private $credit_repo;
    private $payment_repo;

    /**
     * HandleReversal constructor.
     * @param Invoice $invoice
     * @param CreditRepository $credit_repo
     * @param PaymentRepository $payment_repo
     */
    public function __construct(Invoice $invoice, CreditRepository $credit_repo, PaymentRepository $payment_repo)
    {
        $this->credit_repo = $credit_repo;
        $this->payment_repo = $payment_repo;
        $this->invoice = $invoice;
    }

    public function run()
    {
        if (!$this->invoice->isReversable()) {
            return $this->invoice;
        }

        $total_paid = $this->payment_repo->reversePaymentsForInvoice($this->invoice);

        if ($total_paid > 0) {
            // create Credit note
            $this->createCreditNote($total_paid);
        }

        $this->invoice->ledger()->updateBalance($balance_remaining * -1, $notes);

        // update customer
        $this->updateCustomer($total_paid);

        // update invoice
        $this->updateInvoice();

        event(new InvoiceWasReversed($this->invoice));

        return $this->invoice;
    }

    private function createCreditNote(float $total_paid)
    {
           $credit = CreditFactory::create($this->invoice->account, $this->invoice->user, $this->invoice->customer);
           $credit->customer_id = $this->invoice->customer_id;
           $notes = "Credit for reversal of " . $this->invoice->getNumber();

            $line_items[] = (new LineItem)
                ->setQuantity(1)
                ->setUnitPrice($total_paid)
                ->setNotes($notes)
                ->toObject();

            $credit = $this->credit_repo->save(['line_items' => $line_items], $credit);

            $this->credit_repo->markSent($credit);
    }

    private function updateInvoice(): bool
    {
        $this->invoice->setBalance(0);
        $this->invoice->setStatus(Invoice::STATUS_REVERSED);
        $this->invoice->save();

        return true;
    }

    private function updateCustomer(float $total_paid): bool
    {
        $balance_remaining = $this->invoice->balance;
        
        $customer = $this->invoice->customer;
        $customer->increaseBalance($balance_remaining * -1);
        $customer->setPaidToDate($total_paid * -1);
        $customer->save();

        return true;
    }
}
