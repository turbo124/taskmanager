<?php
/**
 * Created by PhpStorm.
 * User: michael.hampton
 * Date: 02/12/2019
 * Time: 15:58
 */

namespace App\Transformations;

use App\Credit;
use App\CreditInvitation;
use App\Email;


trait CreditTransformable
{

    /**
     * Transform the credit
     *
     * @param Credit $credit
     * @return Credit
     */
    protected function transformCredit(Credit $credit)
    {
        return [
        'id' => (int)$credit->id,
        'created_at' => $credit->created_at,
        'user_id' => (int)$credit->user_id,
        'company_id' => (int)$credit->company_id ?: null,
        'public_notes' => $credit->public_notes ?: '',
        'private_notes' => $credit->private_notes ?: '',
        'number' => $credit->number ?: '',
        'customer_id' => (int)$credit->customer_id,
        'date' => $credit->date ?: '',
        'due_date' => $credit->due_date ?: '',
        'design_id' => (int)$credit->design_id,
        'invitations' => $this->transformCreditInvitations($credit->invitations),
        'total' => (float) $credit->total,
        'balance' => (float)$credit->balance,
        'sub_total' => (float)$credit->sub_total,
        'tax_total' => (float)$credit->tax_total,
        'status_id' => (int)$credit->status_id,
        'discount_total' => (float)$credit->discount_total,
        'deleted_at' => $credit->deleted_at,
        'terms' => (string)$credit->terms ?: '',
        'footer' => (string)$credit->footer ?: '',
        'line_items' => $credit->line_items ?: (array)[],
        'custom_value1' => (string)$credit->custom_value1 ?: '',
        'custom_value2' => (string)$credit->custom_value2 ?: '',
        'custom_value3' => (string)$credit->custom_value3 ?: '',
        'custom_value4' => (string)$credit->custom_value4 ?: '',
        'custom_surcharge1' => (float)$credit->custom_surcharge1,
        'custom_surcharge2' => (float)$credit->custom_surcharge2,
        'custom_surcharge3' => (float)$credit->custom_surcharge3,
        'custom_surcharge4' => (float)$credit->custom_surcharge4,
        'custom_surcharge_tax1' => (bool)$credit->custom_surcharge_tax1,
        'custom_surcharge_tax2' => (bool)$credit->custom_surcharge_tax2,
        'custom_surcharge_tax3' => (bool)$credit->custom_surcharge_tax3,
        'custom_surcharge_tax4' => (bool)$credit->custom_surcharge_tax4,
        'emails' => $this->transformCreditEmails($credit->emails()),
       ];
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformCreditInvitations($invitations)
    {
        if (empty($invitations)) {
            return [];
        }

        return $invitations->map(function (CreditInvitation $invitation) {
            return (new CreditInvitationTransformable())->transformCreditInvitation($invitation);
        })->all();
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformCreditEmails($emails)
    {

        if ($emails->count() === 0) {
            return [];
        }

        return $emails->map(function (Email $email) {
            return (new EmailTransformable())->transformEmail($email);
        })->all();
    }
}
