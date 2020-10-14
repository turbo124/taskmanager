<?php

namespace App\Services;

use App\Components\InvoiceCalculator\Invoice;
use App\Components\InvoiceCalculator\LineItem;
use App\Jobs\Email\SendEmail;
use App\Models\ContactInterface;
use App\Models\CustomerContact;
use ReflectionClass;

class ServiceBase
{
    protected array $config = [];
    private $entity;

    public function __construct($entity, array $config = [])
    {
        $this->entity = $entity;
        $this->config = $config;
    }

    protected function trigger(string $subject, string $body, $repo)
    {
        if (empty($this->config)) {
            return false;
        }

        if (!empty($this->config['email'])) {
            $this->entity->service()->sendEmail(null, $subject, $body);
        }

        if (!empty($this->config['archive'])) {
            $repo->archive($this->entity);
        }

        return true;
    }

    protected function reverseStatus()
    {
        $this->entity->setStatus($this->entity->previous_status);
        $this->entity->previous_status = null;

        $this->entity->save();
        return $this->entity;
    }

    protected function reverseBalance()
    {
        if (!isset($this->entity->previous_balance) || empty($this->entity->previous_balance)) {
            return $this->entity;
        }

        $previous_balance = $this->entity->previous_balance;
        $customer = $this->entity->customer;
        $customer->increaseBalance($previous_balance);
        $customer->save();

        $this->entity->transaction_service()->createTransaction(
            $previous_balance,
            $customer->balance,
            "Reverse Balance"
        );

        $this->entity->setBalance($previous_balance);
        $this->entity->previous_balance = null;

        $this->entity->save();
        return $this->entity;
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $template
     * @param null $contact
     * @return bool
     */
    protected function sendInvitationEmails(string $subject, string $body, string $template, $contact = null)
    {
        if ($contact !== null) {
            $invitation = $this->entity->invitations->first();

            $section = $invitation->getSection();

            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_' . $section)];
            return $this->dispatchEmail($contact, $subject, $body, $template, $footer, $invitation);
        }

        if ($this->entity->invitations->count() === 0) {
            return false;
        }

        foreach ($this->entity->invitations as $invitation) {
            $section = $invitation->getSection();

            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_' . $section)];

            $this->dispatchEmail($invitation->contact, $subject, $body, $template, $footer, $invitation);
        }

        return true;
    }

    /**
     * @param CustomerContact $contact
     * @param string $subject
     * @param string $body
     * @param string $template
     * @param array $footer
     * @return bool
     */
    private function dispatchEmail(
        ContactInterface $contact,
        string $subject,
        string $body,
        string $template,
        array $footer,
        $invitation = null
    ) {
        if ($contact->send_email && $contact->email) {
            SendEmail::dispatchNow($this->entity, $subject, $body, $template, $contact, $footer);
        }

        $entity_class = (new ReflectionClass($this->entity))->getShortName();
        $event_class = "App\Events\\" . $entity_class . "\\" . $entity_class . "WasEmailed";

        if (class_exists($event_class) && $invitation !== null) {
            event(new $event_class($invitation));
        }

        return true;
    }

    private function getEntityId($line_item) {
        if(!empty($line_item->expense_id)) {
            return $line_item->expense_id;
        }

        if(!empty($line_item->task_id)) {
            return $line_item->task_id;
        }

        if(!empty($line_item->project_id)) {
            return $line_item->project_id;
        }

        if(!empty($line_item->product_id)) {
            return $line_item->product_id;
        }
    }

    protected function calculateTotals($entity)
    {
        if (empty($entity->line_items)) {
            return $entity;
        }

        $objInvoice = new Invoice($entity);

        foreach ($entity->line_items as $line_item) {
            $objLine = (new LineItem($entity))
                ->setQuantity($line_item->quantity)
                ->setAttributeId(isset($line_item->attribute_id) ? $line_item->attribute_id : 0)
                ->setUnitPrice($line_item->unit_price)
                ->setProductId($this->getEntityId($line_item))
                ->setSubTotal(isset($line_item->sub_total) ? $line_item->sub_total : 0)
                ->setTransactionFee(isset($line_item->transaction_fee) ? $line_item->transaction_fee : 0)
                ->setTypeId(!isset($line_item->type_id) ? 1 : $line_item->type_id)
                ->setUnitTax(isset($line_item->unit_tax) ? $line_item->unit_tax : 0)
                ->setTaxRateName(isset($line_item->tax_rate_name) ? $line_item->tax_rate_name : '')
                ->setTaxRateId(isset($line_item->tax_rate_id) ? $line_item->tax_rate_id : null)
                ->setUnitDiscount($line_item->unit_discount)
                ->setIsAmountDiscount(isset($entity->is_amount_discount) ? $entity->is_amount_discount : false)
                ->setInclusiveTaxes($entity->account->settings->inclusive_taxes)
                ->setNotes(!empty($line_item->notes) ? $line_item->notes : '')
                ->setDescription(!empty($line_item->description) ? $line_item->description : '')
                ->build();


            $objInvoice->addItem($objLine->toObject());
        }

        $objInvoice
            ->setBalance($entity->balance)
            ->setInclusiveTaxes($entity->account->settings->inclusive_taxes)
            ->setTaxRate($entity->tax_rate)
            ->setDiscountTotal(isset($entity->discount_total) ? $entity->discount_total : 0)
            ->setPartial($entity->partial)
            ->build();

        return $objInvoice->rebuildEntity();
    }
}
