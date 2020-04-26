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

        // update invoice
        $old_balance = $this->invoice->balance;
        $this->invoice->setBalance(0);
        $this->invoice->setStatus(Invoice::STATUS_CANCELLED);
        $this->invoice->save();

        $this->invoice->ledger()->updateBalance($old_balance, "Invoice cancellation");

        // update customer
        $customer = $this->invoice->customer;
        $customer->setBalance($old_balance);
        $customer->save();

        event(new InvoiceWasCancelled($this->invoice));

        return $this->invoice;
    }

}
