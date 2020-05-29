<?php

namespace App\Jobs\RecurringInvoice;

use App\Account;
use App\Factory\InvoiceToRecurringInvoiceFactory;
use App\Invoice;
use App\Repositories\RecurringInvoiceRepository;
use App\RecurringInvoice;
use App\Factory\RecurringInvoiceFactory;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SaveRecurringInvoice
{
    use Dispatchable;

    /**
     * @var array
     */
    private array $request;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * SaveRecurringInvoice constructor.
     * @param array $request
     * @param Account $account
     * @param Invoice $invoice
     */
    public function __construct(array $request, Account $account, Invoice $invoice)
    {
        $this->request = $request;
        $this->account = $account;
        $this->invoice = $invoice;
    }

    /**
     * @return RecurringInvoice|null
     */
    public function handle(): ?RecurringInvoice
    {
        if (!empty($this->request['recurring'])) {
            $recurring = json_decode($this->request['recurring'], true);
            $arrRecurring['start_date'] = $recurring['start_date'];
            $arrRecurring['end_date'] = $recurring['end_date'];
            $arrRecurring['frequency'] = $recurring['frequency'];
            $arrRecurring['recurring_due_date'] = $recurring['recurring_due_date'];
            $recurringInvoice = (new RecurringInvoiceRepository(new RecurringInvoice))->save(
                $arrRecurring,
                InvoiceToRecurringInvoiceFactory::create($this->invoice)
            );

            return $recurringInvoice;
        }

        return null;
    }
}
