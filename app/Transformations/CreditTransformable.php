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
        $prop = new Credit();
        $prop->number = $credit->number ?: '';
        $prop->id = (int)$credit->id;
        $prop->customer_id = (int)$credit->customer_id;
        $prop->date = $credit->date ?: '';
        $prop->total = (float)$credit->total;
        $prop->balance = (float)$credit->balance;
        $prop->invitations = $this->transformCreditInvitations($credit->invitations);
        $prop->deleted_at = $credit->deleted_at;
        $prop->user_id = $credit->user_id;
        $prop->status_id = (int)$credit->status_id;
        $prop->assigned_user_id = $credit->assigned_user_id;
        $prop->invoice_id = (int)($credit->invoice_id ?: 1);
        $prop->due_date = $credit->due_date ?: '';
        $prop->sub_total = (float)$credit->sub_total;
        $prop->tax_total = (float)$credit->tax_total;
        $prop->discount_total = (float)$credit->discount_total;
        $prop->design_id = (int)$credit->design_id;
        $prop->terms = $credit->terms ?: '';
        $prop->footer = $credit->footer ?: '';
        $prop->public_notes = $credit->public_notes ?: '';
        $prop->line_items = $credit->line_items ?: (array)[];
        $prop->private_notes = $credit->private_notes ?: '';
        $prop->custom_value1 = $credit->custom_value1 ?: '';
        $prop->custom_value2 = $credit->custom_value2 ?: '';
        $prop->custom_value3 = $credit->custom_value3 ?: '';
        $prop->custom_value4 = $credit->custom_value4 ?: '';
        $prop->custom_surcharge1 = (float)$credit->custom_surcharge1;
        $prop->custom_surcharge2 = (float)$credit->custom_surcharge2;
        $prop->custom_surcharge3 = (float)$credit->custom_surcharge3;
        $prop->custom_surcharge4 = (float)$credit->custom_surcharge4;
        $prop->custom_surcharge_tax1 = (bool)$credit->custom_surcharge_tax1;
        $prop->custom_surcharge_tax2 = (bool)$credit->custom_surcharge_tax2;
        $prop->custom_surcharge_tax3 = (bool)$credit->custom_surcharge_tax3;
        $prop->custom_surcharge_tax4 = (bool)$credit->custom_surcharge_tax4;
        $prop->created_at = $credit->created_at;
        $prop->emails = $this->transformCreditEmails($credit->emails());

        return $prop;
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
