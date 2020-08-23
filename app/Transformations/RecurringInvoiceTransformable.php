<?php

namespace App\Transformations;

use App\Models\RecurringInvoice;
use App\Models\Audit;
use App\Models\Email;
use App\Models\File;
use App\Models\Invoice;
use App\Models\InvoiceInvitation;

trait RecurringInvoiceTransformable
{

    /**
     * @param RecurringInvoice $invoice
     * @return array
     */
    protected function transformRecurringInvoice(RecurringInvoice $invoice)
    {
        return [
            'id'                  => (int)$invoice->id,
            'number'              => $invoice->number,
            'customer_id'         => $invoice->customer_id,
            'date'                => $invoice->date,
            'due_date'            => $invoice->due_date,
            'start_date'          => $invoice->start_date ?: '',
            'end_date'            => $invoice->end_date ?: '',
            'frequency'           => (int)$invoice->frequency,
            'total'               => $invoice->total,
            'sub_total'           => $invoice->sub_total,
            'tax_total'           => $invoice->tax_total,
            'discount_total'      => $invoice->discount_total,
            'currency_id'         => (int)$invoice->currency_id ?: null,
            'exchange_rate'       => (float)$invoice->exchange_rate,
            'deleted_at'          => $invoice->deleted_at,
            'created_at'          => $invoice->created_at,
            'status_id'           => $invoice->status_id,
            'public_notes'        => $invoice->public_notes ?: '',
            'private_notes'       => $invoice->private_notes ?: '',
            'terms'               => $invoice->terms,
            'footer'              => $invoice->footer,
            'line_items'          => $invoice->line_items,
            'custom_value1'       => $invoice->custom_value1 ?: '',
            'custom_value2'       => $invoice->custom_value2 ?: '',
            'custom_value3'       => $invoice->custom_value3 ?: '',
            'custom_value4'       => $invoice->custom_value4 ?: '',
            'transaction_fee'     => (float)$invoice->transaction_fee,
            'shipping_cost'       => (float)$invoice->shipping_cost,
            'gateway_fee'         => (float)$invoice->gateway_fee,
            'gateway_percentage'  => (bool)$invoice->gateway_percentage,
            'transaction_fee_tax' => (bool)$invoice->transaction_fee_tax,
            'shipping_cost_tax'   => (bool)$invoice->shipping_cost_tax,
            'audits'              => $this->transformAuditsForRecurringInvoice($invoice->audits),
            'files'               => $this->transformInvoiceFiles($invoice->files),
            'invitations'         => []

        ];
    }
   
     /**
     * @param $files
     * @return array
     */
    private function transformInvoiceFiles($files)
    {
        if (empty($files)) {
            return [];
        }

        return $files->map(
            function (File $file) {
                return (new FileTransformable())->transformFile($file);
            }
        )->all();
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformRecurringInvoiceInvitations($invitations)
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

    public function transformAuditsForRecurringInvoice($audits)
    {
        if (empty($audits)) {
            return [];
        }

        return $audits->map(
            function (Audit $audit) {
                return (new AuditTransformable)->transformAudit($audit);
            }
        )->all();
    }

}
