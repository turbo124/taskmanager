<?php

namespace App\Transformations;

use App\Models\Audit;
use App\Models\Email;
use App\Models\File;
use App\Models\Quote;
use App\Models\RecurringQuote;
use App\Models\RecurringQuoteInvitation;

trait RecurringQuoteTransformable
{
    /**
     * @param RecurringQuote $quote
     * @return array
     */
    protected function transformRecurringQuote(RecurringQuote $quote)
    {
        return [
            'id'                   => (int)$quote->id,
            'number'               => $quote->number,
            'customer_id'          => $quote->customer_id,
            'date'                 => $quote->date,
            'due_date'             => $quote->due_date,
            'start_date'           => $quote->start_date ?: '',
            'expiry_date'          => $quote->expiry_date ?: '',
            'date_to_send'         => $quote->date_to_send ?: '',
            'last_sent_date'       => $quote->last_sent_date ?: '',
            'frequency'            => (int)$quote->frequency,
            'grace_period'         => (int)$quote->grace_period,
            'auto_billing_enabled' => (bool)$quote->auto_billing_enabled,
            'total'                => $quote->total,
            'sub_total'            => $quote->sub_total,
            'tax_total'            => $quote->tax_total,
            'discount_total'       => $quote->discount_total,
            'currency_id'          => (int)$quote->currency_id ?: null,
            'exchange_rate'        => (float)$quote->exchange_rate,
            'deleted_at'           => $quote->deleted_at,
            'created_at'           => $quote->created_at,
            'status_id'            => $quote->status_id,
            'public_notes'         => $quote->public_notes ?: '',
            'private_notes'        => $quote->private_notes ?: '',
            'terms'                => $quote->terms,
            'footer'               => $quote->footer,
            'line_items'           => $quote->line_items,
            'custom_value1'        => $quote->custom_value1 ?: '',
            'custom_value2'        => $quote->custom_value2 ?: '',
            'custom_value3'        => $quote->custom_value3 ?: '',
            'custom_value4'        => $quote->custom_value4 ?: '',
            'transaction_fee'      => (float)$quote->transaction_fee,
            'shipping_cost'        => (float)$quote->shipping_cost,
            'gateway_fee'          => (float)$quote->gateway_fee,
            'gateway_percentage'   => (bool)$quote->gateway_percentage,
            'transaction_fee_tax'  => (bool)$quote->transaction_fee_tax,
            'shipping_cost_tax'    => (bool)$quote->shipping_cost_tax,
            'audits'               => $this->transformAuditsForRecurringQuote($quote->audits),
            'files'                => $this->transformRecurringQuoteFiles($quote->files),
            'invitations'          => [],
            'quotes'               => $this->transformQuotesCreated($quote->quotes)

        ];
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

    /**
     * @param $files
     * @return array
     */
    private function transformRecurringQuoteFiles($files)
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

    private function transformQuotesCreated($quotes)
    {
        if ($quotes->count() === 0) {
            return [];
        }

        return $quotes->map(
            function (Quote $quote) {
                return (new QuoteTransformable())->transformQuote($quote);
            }
        )->all();
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
            function (RecurringQuoteInvitation $invitation) {
                return (new RecurringQuoteInvitationTransformable())->transformInvoiceInvitation($invitation);
            }
        )->all();
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformRecurringQuoteEmails($emails)
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
