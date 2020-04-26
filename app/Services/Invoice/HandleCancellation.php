<?php
namespace App\Services\Invoice;

use App\Events\Payment\PaymentWasCreated;
use App\Factory\CreditFactory;
use App\Factory\PaymentFactory;
use App\Events\Invoice\InvoiceWasCancelled;
use App\Customer;
use App\Invoice;
use App\Payment;
use App\Paymentable;
use App\Services\Payment\PaymentService;

class HandleCancellation
{

    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function run()
    {
        if (!$this->invoice->isCancellable()) {
            return $this->invoice;
        }

        $old_balance = $this->invoice->balance;
        $this->invoice->balance = 0;
        $this->invoice->status_id = Invoice::STATUS_CANCELLED;
        $this->invoice->save();

        $this->invoice->ledger()->updateBalance($old_balance, "Invoice cancellation");

        //adjust client balance
        $this->invoice->customer->setBalance($old_balance);

        event(new InvoiceWasCancelled($this->invoice));

        return $this->invoice;
    }

}
