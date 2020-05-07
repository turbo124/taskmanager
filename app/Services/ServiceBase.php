<?php

namespace App\Services;

use App\Helpers\InvoiceCalculator\LineItem;
use App\Jobs\Email\SendEmail;

class ServiceBase
{
    private $entity;

    private array $config = [];

    public function __construct($entity, array $config = [])
    {
        $this->entity = $entity;
        $this->config = $config;
    }

    protected function trigger(string $subject, string $body, $repo)
    {
        if(empty($this->config)) {
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
                ->setUnitPrice($line_item->unit_price)
                ->setProductId($line_item->product_id)
                ->setSubTotal(isset($line_item->sub_total) ? $line_item->sub_total : 0)
                ->setTypeId(!isset($line_item->type_id) ? 1 : $line_item->type_id)
                ->setUnitTax($line_item->unit_tax)
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
            ->setPartial($entity->partial)
            ->build();

        return $objInvoice->getEntity();

    }
}
