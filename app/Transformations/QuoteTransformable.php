<?php

namespace App\Transformations;

use App\Audit;
use App\Email;
use App\File;
use App\Quote;
use App\QuoteInvitation;
use App\Repositories\CustomerRepository;
use App\Customer;

trait QuoteTransformable
{
    use FileTransformable;

    /**
     * @param Quote $quote
     * @return array
     */
    protected function transformQuote(Quote $quote)
    {
        return [
            'id'                  => (int)$quote->id,
            'created_at'          => $quote->created_at,
            'user_id'             => (int)$quote->user_id,
            'company_id'          => (int)$quote->company_id ?: null,
            'public_notes'        => $quote->public_notes ?: '',
            'private_notes'       => $quote->private_notes ?: '',
            'number'              => $quote->number ?: '',
            'customer_id'         => (int)$quote->customer_id,
            'date'                => $quote->date ?: '',
            'due_date'            => $quote->due_date ?: '',
            'design_id'           => (int)$quote->design_id,
            'invitations'         => $this->transformQuoteInvitations($quote->invitations),
            'total'               => $quote->total,
            'balance'             => (float)$quote->balance,
            'sub_total'           => (float)$quote->sub_total,
            'tax_total'           => (float)$quote->tax_total,
            'status_id'           => (int)$quote->status_id,
            'discount_total'      => (float)$quote->discount_total,
            'deleted_at'          => $quote->deleted_at,
            'terms'               => (string)$quote->terms ?: '',
            'footer'              => (string)$quote->footer ?: '',
            'line_items'          => $quote->line_items ?: (array)[],
            'custom_value1'       => (string)$quote->custom_value1 ?: '',
            'custom_value2'       => (string)$quote->custom_value2 ?: '',
            'custom_value3'       => (string)$quote->custom_value3 ?: '',
            'custom_value4'       => (string)$quote->custom_value4 ?: '',
            'transaction_fee'     => (float)$quote->transaction_fee,
            'shipping_cost'       => (float)$quote->shipping_cost,
            'gateway_fee'         => (float)$credit->gateway_fee,
            'gateway_percentage'  => (bool)$credit->gateway_percentage,
            'transaction_fee_tax' => (bool)$quote->transaction_fee_tax,
            'shipping_cost_tax'   => (bool)$quote->shipping_cost_tax,
            'emails'              => $this->transformQuoteEmails($quote->emails()),
            'audits'              => $this->transformAuditsForQuote($quote->audits),
            'files'               => $this->transformQuoteFiles($quote->files)
        ];
    }

    /**
     * @param $files
     * @return array
     */
    private function transformQuoteFiles($files)
    {
        if (empty($files)) {
            return [];
        }

        return $files->map(
            function (File $file) {
                return $this->transformFile($file);
            }
        )->all();
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformQuoteInvitations($invitations)
    {
        if (empty($invitations)) {
            return [];
        }

        return $invitations->map(
            function (QuoteInvitation $invitation) {
                return (new QuoteInvitationTransformable())->transformQuoteInvitations($invitation);
            }
        )->all();
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformQuoteEmails($emails)
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

    public function transformAuditsForQuote($audits)
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
