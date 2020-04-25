<?php

namespace App\Transformations;

use App\Email;
use App\Invoice;
use App\InvoiceInvitation;
use App\Repositories\CustomerRepository;
use App\Customer;

trait InvoiceTransformable
{

    /**
     * Transform the invoice
     *
     * @param Invoice $invoice
     * @return Invoice
     */
    protected function transformInvoice(Invoice $invoice)
    {
        $prop = new Invoice;

        $prop->id = (int)$invoice->id;
        $prop->created_at = $invoice->created_at;
        $customer = $invoice->customer;
        $prop->user_id = (int)$invoice->user_id;
        $prop->company_id = (int)$invoice->company_id ?: null;
        $prop->public_notes = $invoice->public_notes ?: '';
        $prop->private_notes = $invoice->private_notes ?: '';
        $prop->number = $invoice->number ?: '';
        $prop->customer_id = (int)$invoice->customer_id;
        $prop->date = $invoice->date ?: '';
        $prop->due_date = $invoice->due_date ?: '';
        $prop->next_send_date = $invoice->date ?: '';
        $prop->design_id = (int)$invoice->design_id;
        $prop->invitations = $this->transformInvoiceInvitations($invoice->invitations);


        $prop->total = $invoice->total;
        $prop->user_id = $invoice->user_id;
        $prop->balance = (float)$invoice->balance;
        $prop->sub_total = (float)$invoice->sub_total;
        $prop->tax_total = (float)$invoice->tax_total;
        $prop->status_id = (int)$invoice->status_id;
        $prop->discount_total = (float)$invoice->discount_total;
        $prop->deleted_at = $invoice->deleted_at;
        $prop->terms = (string)$invoice->terms ?: '';
        $prop->footer = (string)$invoice->footer;
        $prop->line_items = $invoice->line_items ?: (array)[];
        $prop->custom_value1 = $invoice->custom_value1 ?: '';
        $prop->custom_value2 = $invoice->custom_value2 ?: '';
        $prop->custom_value3 = $invoice->custom_value3 ?: '';
        $prop->custom_value4 = $invoice->custom_value4 ?: '';
        $prop->custom_surcharge1 = (float)$invoice->custom_surcharge1;
        $prop->custom_surcharge2 = (float)$invoice->custom_surcharge2;
        $prop->custom_surcharge3 = (float)$invoice->custom_surcharge3;
        $prop->custom_surcharge4 = (float)$invoice->custom_surcharge4;
        $prop->custom_surcharge_tax1 = (bool)$invoice->custom_surcharge_tax1;
        $prop->custom_surcharge_tax2 = (bool)$invoice->custom_surcharge_tax2;
        $prop->custom_surcharge_tax3 = (bool)$invoice->custom_surcharge_tax3;
        $prop->custom_surcharge_tax4 = (bool)$invoice->custom_surcharge_tax4;
        $prop->last_sent_date = $invoice->last_sent_date ?: '';
        $prop->emails = $this->transformEmails($invoice->emails());
        return $prop;
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformInvoiceInvitations($invitations)
    {
        if (empty($invitations)) {
            return [];
        }

        return $invitations->map(function (InvoiceInvitation $invitation) {
            return (new InvoiceInvitationTransformable())->transformInvoiceInvitation($invitation);
        })->all();
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformEmails($emails)
    {

        if ($emails->count() === 0) {
            return [];
        }

        return $emails->map(function (Email $email) {
            return (new EmailTransformable())->transformEmail($email);
        })->all();
    }
}
