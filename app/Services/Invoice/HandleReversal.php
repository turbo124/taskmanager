<?php
namespace App\Services\Invoice;

use App\Factory\CreditFactory;
use App\Events\Invoice\InvoiceWasReversed;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;

class HandleReversal
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
        /* Check again!! */
        if (!$this->invoice->isReversable()) {
            return $this->invoice;
        }

        $balance_remaining = $this->invoice->balance;

        $total_paid = $this->payment_repo->reversePaymentsForInvoice($this->invoice);

        /*Adjust payment applied and the paymentables to the correct amount */


        /* Generate a credit for the $total_paid amount */
        $notes = "Credit for reversal of " . $this->invoice->number;

        if ($total_paid > 0) {
            $credit = CreditFactory::create($this->invoice->account_id, $this->invoice->user_id, $this->invoice->customer);
            $credit->customer_id = $this->invoice->customer_id;

            $line_items[] = (new LineItem)
                ->setQuantity(1)
                ->setUnitPrice($total_paid)
                ->setNotes($notes)
                ->toObject();

            $credit = $this->credit_repo->save(['line_items' => $line_items], $credit);

            $this->credit_repo->markSent($credit);
        }
        
        /* Set invoice balance to 0 */
        $this->invoice->ledger()->updateBalance($balance_remaining * -1, $notes);

        $this->invoice->customer->setBalance($balance_remaining * -1);
        $this->invoice->customer->setPaidToDate($total_paid * -1);

        $this->invoice->balance = 0;
        $this->invoice->status_id = Invoice::STATUS_REVERSED;
        $this->invoice->save();

        event(new InvoiceWasReversed($this->invoice));

        return $this->invoice;
    }
}
