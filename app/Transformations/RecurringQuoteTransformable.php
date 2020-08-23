<?php

namespace App\Transformations;

use App\Models\RecurringQuote;
use App\Models\Audit;
use App\Models\Email;
use App\Models\File;
use App\Models\Invoice;
use App\Models\InvoiceInvitation;

trait RecurringQuoteTransformable
{

    /**
     * @param RecurringQuote $quote
     * @return array
     */
    protected function transformRecurringQuote(RecurringQuote $quote)
    {
        return [
            'id'                  => (int)$quote->id,
            'number'              => $quote->number,
            'customer_id'         => $quote->customer_id,
            'date'                => $quote->date,
            'due_date'            => $quote->due_date,
            'start_date'          => $quote->start_date ?: '',
            'end_date'            => $quote->end_date ?: '',
            'frequency'           => (int)$quote->frequency,
            'total'               => $quote->total,
            'sub_total'           => $quote->sub_total,
            'tax_total'           => $quote->tax_total,
            'discount_total'      => $quote->discount_total,
            'currency_id'         => (int)$quote->currency_id ?: null,
            'exchange_rate'       => (float)$quote->exchange_rate,
            'deleted_at'          => $quote->deleted_at,
            'created_at'          => $quote->created_at,
            'status_id'           => $quote->status_id,
            'public_notes'        => $quote->public_notes ?: '',
            'private_notes'       => $quote->private_notes ?: '',
            'terms'               => $quote->terms,
            'footer'              => $quote->footer,
            'line_items'          => $quote->line_items,
            'custom_value1'       => $quote->custom_value1 ?: '',
            'custom_value2'       => $quote->custom_value2 ?: '',
            'custom_value3'       => $quote->custom_value3 ?: '',
            'custom_value4'       => $quote->custom_value4 ?: '',
            'transaction_fee'     => (float)$quote->transaction_fee,
            'shipping_cost'       => (float)$quote->shipping_cost,
            'gateway_fee'         => (float)$quote->gateway_fee,
            'gateway_percentage'  => (bool)$quote->gateway_percentage,
            'transaction_fee_tax' => (bool)$quote->transaction_fee_tax,
            'shipping_cost_tax'   => (bool)$quote->shipping_cost_tax,
            'audits'              => $this->transformAuditsForRecurringQuote($quote->audits),
            'files'               => $this->transformInvoiceFiles($quote->files)

        ];
    }

    

    /**
     * @param $invitations
     * @return array
     */
    private function transformRecurringQuoteInvitations($invitations)
    {
        if (empty($invitations)) {
            return [];
        }

        return $invitations->map(
            function (InvoiceInvitation $invitation) {
                return (new QuoteInvitationTransformable())->transformInvoiceInvitation($invitation);
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

    public function transformAuditsForRecurringQuote($audits)
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
