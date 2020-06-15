<?php

namespace App\Services;

use App\Helpers\InvoiceCalculator\LineItem;
use App\Jobs\Email\SendEmail;

class ServiceBase
{
    private $entity;

    protected array $config = [];

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

        $this->entity->setBalance($this->entity->previous_balance);
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
        if ($this->entity->invitations->count() === 0) {
            return false;
        }

        foreach ($this->entity->invitations as $invitation) {
            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];

            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->entity, $subject, $body, $template, $invitation->contact, $footer);
            }
        }

        return true;
    }

    protected function calculateTotals($entity)
    {
        if (empty($entity->line_items)) {
            return $entity;
        }

        $objInvoice = new \App\Helpers\InvoiceCalculator\Invoice($entity);

        foreach ($entity->line_items as $line_item) {
            $objLine = (new LineItem($entity))
                ->setQuantity($line_item->quantity)
                ->setAttributeId(isset($line_item->attribute_id) ? $line_item->attribute_id : 0)
                ->setUnitPrice($line_item->unit_price)
                ->setProductId($line_item->product_id)
                ->setSubTotal(isset($line_item->sub_total) ? $line_item->sub_total : 0)
                ->setTypeId(!isset($line_item->type_id) ? 1 : $line_item->type_id)
                ->setUnitTax(isset($line_item->unit_tax) ? $line_item->unit_tax : 0)
                ->setTaxRateName(isset($line_item->tax_rate_name) ? $line_item->tax_rate_name : '')
                ->setTaxRateId(isset($line_item->tax_rate_id) ? $line_item->tax_rate_id : null)
                ->setUnitDiscount($line_item->unit_discount)
                ->setIsAmountDiscount(isset($entity->is_amount_discount) ? $entity->is_amount_discount : false)
                ->setInclusiveTaxes($entity->account->settings->inclusive_taxes)
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

        return $objInvoice->getEntity();
    }
}
