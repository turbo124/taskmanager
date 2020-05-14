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
     * @param Invoice $invoice
     * @return array
     */
    protected function transformInvoice(Invoice $invoice)
    {
        return [
            'id'                    => (int)$invoice->id,
            'created_at'            => $invoice->created_at,
            //'customer' => $invoice->customer,
            'user_id'               => (int)$invoice->user_id,
            'company_id'            => (int)$invoice->company_id ?: null,
            'public_notes'          => $invoice->public_notes ?: '',
            'private_notes'         => $invoice->private_notes ?: '',
            'number'                => $invoice->number ?: '',
            'customer_id'           => (int)$invoice->customer_id,
            'date'                  => $invoice->date ?: '',
            'due_date'              => $invoice->due_date ?: '',
            'next_send_date'        => $invoice->date ?: '',
            'design_id'             => (int)$invoice->design_id,
            'invitations'           => $this->transformInvoiceInvitations($invoice->invitations),
            'total'                 => $invoice->total,
            'balance'               => (float)$invoice->balance,
            'sub_total'             => (float)$invoice->sub_total,
            'tax_total'             => (float)$invoice->tax_total,
            'status_id'             => (int)$invoice->status_id,
            'discount_total'        => (float)$invoice->discount_total,
            'deleted_at'            => $invoice->deleted_at,
            'terms'                 => (string)$invoice->terms ?: '',
            'footer'                => (string)$invoice->footer ?: '',
            'line_items'            => $invoice->line_items ?: (array)[],
            'custom_value1'         => (string)$invoice->custom_value1 ?: '',
            'custom_value2'         => (string)$invoice->custom_value2 ?: '',
            'custom_value3'         => (string)$invoice->custom_value3 ?: '',
            'custom_value4'         => (string)$invoice->custom_value4 ?: '',
            'custom_surcharge1'     => (float)$invoice->custom_surcharge1,
            'custom_surcharge2'     => (float)$invoice->custom_surcharge2,
            'custom_surcharge3'     => (float)$invoice->custom_surcharge3,
            'custom_surcharge4'     => (float)$invoice->custom_surcharge4,
            'custom_surcharge_tax1' => (bool)$invoice->custom_surcharge_tax1,
            'custom_surcharge_tax2' => (bool)$invoice->custom_surcharge_tax2,
            'custom_surcharge_tax3' => (bool)$invoice->custom_surcharge_tax3,
            'custom_surcharge_tax4' => (bool)$invoice->custom_surcharge_tax4,
            'last_sent_date'        => $invoice->last_sent_date ?: '',
            'emails'                => $this->transformEmails($invoice->emails()),
        ];
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

        return $invitations->map(
            function (InvoiceInvitation $invitation) {
                return (new InvoiceInvitationTransformable())->transformInvoiceInvitation($invitation);
            }
        )->all();
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

        return $emails->map(
            function (Email $email) {
                return (new EmailTransformable())->transformEmail($email);
            }
        )->all();
    }
}
