<?php

namespace App\Services\Quote;

use App\Events\PurchaseOrder\PurchaseOrderWasApproved;
use App\Events\PurchaseOrder\PurchaseOrderWasEmailed;
use App\Factory\QuoteToRecurringPurchaseOrderFactory;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Models\RecurringPurchaseOrder;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\RecurringQuoteRepository;
use App\Services\PurchaseOrder\MarkSent;
use App\Services\ServiceBase;

class PurchaseOrderService extends ServiceBase
{
    protected PurchaseOrder $po;

    public function __construct(PurchaseOrder $po)
    {
        $config = [
            'email'   => $po->customer->getSetting('should_email_purchase_order'),
            'archive' => $po->customer->getSetting('should_archive_purchase_order')
        ];

        parent::__construct($quote, $config);
        $this->po = $po;
    }

    public function approve(PurchaseOrderRepository $po_repo): ?PurchaseOrder
    {
        if ($this->po->status_id != PurchaseOrder::STATUS_SENT) {
            return null;
        }

        $this->po->setStatus(PurchaseOrder::STATUS_APPROVED);
        $this->po->save();

        event(new PurchaseOrderWasApproved($this->po));

        // trigger
        $subject = trans('texts.purchase_order_approved_subject');
        $body = trans('texts.purchase_order_approved_body');
        $this->trigger($subject, $body, $po_repo);

        return $this->po;
    }

    /**
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function generatePdf($contact = null, $update = false)
    {
        return (new GeneratePdf($this->po, $contact, $update))->execute();
    }

    /**
     * @param null $contact
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject, $body, $template = 'purchase_order'): ?PurchaseOrder
    {
        if (!$this->sendInvitationEmails($subject, $body, $template, $contact)) {
            return null;
        }

        event(new PurchaseOrderWasEmailed($this->po->invitations->first()));
        return $this->po;
    }

    /**
     * @return PurchaseOrder
     */
    public function calculateInvoiceTotals(): PurchaseOrder
    {
        return $this->calculateTotals($this->po);
    }

    /**
     * @param OrderRepository $order_repository
     * @return Order|null
     */
    public function convertQuoteToOrder(OrderRepository $order_repository)
    {
        return (new ConvertQuoteToOrder($this->quote, $order_repository))->execute();
    }

    /**
     * @param InvoiceRepository $invoice_repository
     * @return Invoice|null
     */
    public function convertQuoteToInvoice(InvoiceRepository $invoice_repository): ?Invoice
    {
        return (new ConvertQuoteToInvoice($this->quote, $invoice_repository))->execute();
    }

    /**
     * @param array $data
     * @return RecurringQuote|null
     */
    public function createRecurringQuote(array $recurring): ?RecurringQuote
    {
        if (empty($recurring)) {
            return null;
        }

        $arrRecurring['start_date'] = $recurring['start_date'];
        $arrRecurring['end_date'] = $recurring['end_date'];
        $arrRecurring['frequency'] = $recurring['frequency'];
        $arrRecurring['recurring_due_date'] = $recurring['recurring_due_date'];
        $recurringQuote = (new RecurringPurchaseOrderRepository(new RecurringPurchaseOrder))->save(
            $arrRecurring,
            PurchaseOrderToRecurringPurchaseOrderFactory::create($this->po)
        );

        $this->quote->recurring_purchase_order_id = $recurringQuote->id;
        $this->quote->save();

        return $recurringQuote;
    }
}
