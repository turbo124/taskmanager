<?php

namespace App\Transformations;

use App\Email;
use App\Quote;
use App\QuoteInvitation;
use App\Repositories\CustomerRepository;
use App\Customer;

trait QuoteTransformable
{

    /**
     * @param Quote $quote
     * @return array
     */
    protected function transformQuote(Quote $quote)
    {
        return [
            'id'                    => (int)$quote->id,
            'created_at'            => $quote->created_at,
            'user_id'               => (int)$quote->user_id,
            'company_id'            => (int)$quote->company_id ?: null,
            'public_notes'          => $quote->public_notes ?: '',
            'private_notes'         => $quote->private_notes ?: '',
            'number'                => $quote->number ?: '',
            'customer_id'           => (int)$quote->customer_id,
            'customer_name'         => (string)$quote->customer->present()->name(),
            'date'                  => $quote->date ?: '',
            'due_date'              => $quote->due_date ?: '',
            'design_id'             => (int)$quote->design_id,
            'invitations'           => $this->transformInvitations($quote->invitations),
            'total'                 => $quote->total,
            'balance'               => (float)$quote->balance,
            'sub_total'             => (float)$quote->sub_total,
            'tax_total'             => (float)$quote->tax_total,
            'status_id'             => (int)$quote->status_id,
            'discount_total'        => (float)$quote->discount_total,
            'deleted_at'            => $quote->deleted_at,
            'terms'                 => (string)$quote->terms ?: '',
            'footer'                => (string)$quote->footer ?: '',
            'line_items'            => $quote->line_items ?: (array)[],
            'custom_value1'         => (string)$quote->custom_value1 ?: '',
            'custom_value2'         => (string)$quote->custom_value2 ?: '',
            'custom_value3'         => (string)$quote->custom_value3 ?: '',
            'custom_value4'         => (string)$quote->custom_value4 ?: '',
            'custom_surcharge1'     => (float)$quote->custom_surcharge1,
            'custom_surcharge2'     => (float)$quote->custom_surcharge2,
            'custom_surcharge_tax1' => (bool)$quote->custom_surcharge_tax1,
            'custom_surcharge_tax2' => (bool)$quote->custom_surcharge_tax2,
            'emails'                => $this->transformQuoteEmails($quote->emails()),
        ];
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformInvitations($invitations)
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
}
