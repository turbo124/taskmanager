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

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var CreditRepository
     */
    private CreditRepository $credit_repo;

    /**
     * @var PaymentRepository
     */
    private PaymentRepository $payment_repo;

    /**
     * @var float|mixed
     */
    private float $balance;

    /**
     * @var string
     */
    private string $note;

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
        $this->balance = $this->invoice->balance;
        $this->note = "Credit for reversal of " . $this->invoice->getNumber();
    }

    public function execute()
    {
        if (!$this->invoice->isReversable()) {
            return $this->invoice;
        }

        $total_paid = $this->payment_repo->reversePaymentsForInvoice($this->invoice);

        if ($total_paid > 0) {
            // create Credit note
            $this->createCreditNote($total_paid);
        }

        $this->invoice->ledger()->updateBalance($this->balance * -1, $this->note);

        // update customer
        $this->updateCustomer($total_paid);

        // update invoice
        $this->updateInvoice();

        event(new InvoiceWasReversed($this->invoice));

        return $this->invoice;
    }

    /**
     * @param float $total_paid
     */
    private function createCreditNote(float $total_paid)
    {
        $credit = CreditFactory::create($this->invoice->account, $this->invoice->user, $this->invoice->customer);

        $line_items[] = (new LineItem)
            ->setQuantity(1)
            ->setUnitPrice($total_paid)
            ->setNotes($this->note)
            ->toObject();

        $credit = $this->credit_repo->save(['line_items' => $line_items], $credit);

        $this->credit_repo->markSent($credit);
    }

    /**
     * @return bool
     */
    private function updateInvoice(): bool
    {
        $this->invoice->setBalance(0);
        $this->invoice->setStatus(Invoice::STATUS_REVERSED);
        $this->invoice->save();

        return true;
    }

    /**
     * @param float $total_paid
     * @return bool
     */
    private function updateCustomer(float $total_paid): bool
    {
        $customer = $this->invoice->customer;
        $customer->increaseBalance($this->balance * -1);
        $customer->increasePaidToDateAmount($total_paid * -1);
        $customer->save();

        return true;
    }
}
