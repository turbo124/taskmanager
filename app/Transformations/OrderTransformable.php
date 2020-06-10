<?php

namespace App\Transformations;

use App\Audit;
use App\Email;
use App\Invoice;
use App\InvoiceInvitation;
use App\Order;
use App\OrderInvitation;
use App\Payment;
use App\Repositories\CustomerRepository;
use App\Customer;

trait OrderTransformable
{

    /**
     * @param Order $order
     * @return array
     */
    protected function transformOrder(Order $order)
    {
        return [
            'id'                  => (int)$order->id,
            'created_at'          => $order->created_at,
            'user_id'             => (int)$order->user_id,
            'company_id'          => (int)$order->company_id ?: null,
            'public_notes'        => $order->public_notes ?: '',
            'private_notes'       => $order->private_notes ?: '',
            'number'              => $order->number ?: '',
            'customer_id'         => (int)$order->customer_id,
            'date'                => $order->date ?: '',
            'due_date'            => $order->due_date ?: '',
            'design_id'           => (int)$order->design_id,
            'invitations'         => $this->transformOrderInvitations($order->invitations),
            'total'               => $order->total,
            'balance'             => (float)$order->balance,
            'sub_total'           => (float)$order->sub_total,
            'tax_total'           => (float)$order->tax_total,
            'status_id'           => (int)$order->status_id,
            'invoice_id'          => (int)$order->invoice_id,
            'discount_total'      => (float)$order->discount_total,
            'deleted_at'          => $order->deleted_at,
            'terms'               => (string)$order->terms ?: '',
            'footer'              => (string)$order->footer ?: '',
            'line_items'          => $order->line_items ?: (array)[],
            'custom_value1'       => (string)$order->custom_value1 ?: '',
            'custom_value2'       => (string)$order->custom_value2 ?: '',
            'custom_value3'       => (string)$order->custom_value3 ?: '',
            'custom_value4'       => (string)$order->custom_value4 ?: '',
            'transaction_fee'     => (float)$order->transaction_fee,
            'shipping_cost'       => (float)$order->shipping_cost,
            'transaction_fee_tax' => (bool)$order->transaction_fee_tax,
            'shipping_cost_tax'   => (bool)$order->shipping_cost_tax,
            'emails'              => $this->transformOrderEmails($order->emails()),
            'audits'              => $this->transformAuditsForOrder($order->audits)
        ];
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformOrderInvitations($invitations)
    {
        if (empty($invitations)) {
            return [];
        }

        return $invitations->map(
            function (OrderInvitation $invitation) {
                return (new OrderInvitationTransformable)->transformOrderInvitation($invitation);
            }
        )->all();
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformOrderEmails($emails)
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

    public function transformAuditsForOrder($audits)
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
