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
     * @return Quote
     */
    protected function transformQuote(Quote $quote)
    {
        $prop = new Quote;

        $prop->id = (int)$quote->id;
        $prop->number = $quote->number ?: '';
        $prop->company_id = $quote->company_id ?: null;
        $prop->public_notes = $quote->public_notes ?: '';
        $prop->private_notes = $quote->private_notes ?: '';
        $prop->customer_id = $quote->customer_id;
        $prop->date = $quote->date ?: '';
        $prop->due_date = $quote->due_date ?: '';
        $prop->total = $quote->total;
        $prop->balance = (float)$quote->balance;
        $prop->status_id = $quote->status_id;
        $prop->design_id = (int)$quote->design_id;
        $prop->next_send_date = $quote->date ?: '';

        $prop->sub_total = $quote->sub_total;
        $prop->deleted_at = $quote->deleted_at;
        $prop->created_at = $quote->created_at;
        $prop->tax_total = $quote->tax_total;
        $prop->discount_total = $quote->discount_total;

        $prop->terms = $quote->terms;
        $prop->footer = $quote->footer ?: '';
        $prop->line_items = $quote->line_items ?: (array)[];
        $prop->invitations = $this->transformInvitations($quote->invitations);
        $prop->custom_value1 = $quote->custom_value1 ?: '';
        $prop->custom_value2 = $quote->custom_value2 ?: '';
        $prop->custom_value3 = $quote->custom_value3 ?: '';
        $prop->custom_value4 = $quote->custom_value4 ?: '';
        $prop->custom_surcharge1 = (float)$quote->custom_surcharge1;
        $prop->custom_surcharge2 = (float)$quote->custom_surcharge2;
        $prop->custom_surcharge3 = (float)$quote->custom_surcharge3;
        $prop->custom_surcharge4 = (float)$quote->custom_surcharge4;
        $prop->custom_surcharge_tax1 = (bool)$quote->custom_surcharge_tax1;
        $prop->custom_surcharge_tax2 = (bool)$quote->custom_surcharge_tax2;
        $prop->custom_surcharge_tax3 = (bool)$quote->custom_surcharge_tax3;
        $prop->custom_surcharge_tax4 = (bool)$quote->custom_surcharge_tax4;
        $prop->uses_inclusive_taxes = (bool)$quote->uses_inclusive_taxes;
        $prop->last_sent_date = $quote->last_sent_date ?: '';
        $prop->invoice_id = (int)($quote->invoice_id ?: 1);
        $prop->emails = $this->transformQuoteEmails($quote->emails());

        return $prop;
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

        return $invitations->map(function (QuoteInvitation $invitation) {
            return (new QuoteInvitationTransformable())->transformQuoteInvitations($invitation);
        })->all();
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

        return $emails->map(function (Email $email) {
            return (new EmailTransformable())->transformEmail($email);
        })->all();
    }
}
