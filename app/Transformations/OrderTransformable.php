<?php

namespace App\Transformations;

use App\Email;
use App\Invoice;
use App\InvoiceInvitation;
use App\Order;
use App\OrderInvitation;
use App\Repositories\CustomerRepository;
use App\Customer;

trait OrderTransformable
{

    /**
     * Transform the invoice
     *
     * @param Invoice $invoice
     * @return Invoice
     */
    protected function transformOrder(Order $order): Order
    {
        $prop = new Order;

        $prop->id = (int)$order->id;
        $prop->created_at = $order->created_at;
        $customer = $order->customer;
        $prop->user_id = (int)$order->user_id;
        $prop->company_id = (int)$order->company_id ?: null;
        $prop->public_notes = $order->public_notes ?: '';
        $prop->private_notes = $order->private_notes ?: '';
        $prop->number = $order->number ?: '';
        $prop->customer_id = (int)$order->customer_id;
        $prop->date = $order->date ?: '';
        $prop->due_date = $order->due_date ?: '';
        $prop->next_send_date = $order->date ?: '';
        $prop->design_id = (int)$order->design_id;
        $prop->invitations = $this->transformOrderInvitations($order->invitations);
        $prop->total = $order->total;
        $prop->user_id = $order->user_id;
        $prop->balance = (float)$order->balance;
        $prop->sub_total = (float)$order->sub_total;
        $prop->tax_total = (float)$order->tax_total;
        $prop->status_id = (int)$order->status_id;
        $prop->discount_total = (float)$order->discount_total;
        $prop->deleted_at = $order->deleted_at;
        $prop->terms = (string)$order->terms ?: '';
        $prop->footer = (string)$order->footer;
        $prop->line_items = $order->line_items ?: (array)[];
        $prop->custom_value1 = $order->custom_value1 ?: '';
        $prop->custom_value2 = $order->custom_value2 ?: '';
        $prop->custom_value3 = $order->custom_value3 ?: '';
        $prop->custom_value4 = $order->custom_value4 ?: '';
        $prop->custom_surcharge1 = (float)$order->custom_surcharge1;
        $prop->custom_surcharge2 = (float)$order->custom_surcharge2;
        $prop->custom_surcharge3 = (float)$order->custom_surcharge3;
        $prop->custom_surcharge4 = (float)$order->custom_surcharge4;
        $prop->custom_surcharge_tax1 = (bool)$order->custom_surcharge_tax1;
        $prop->custom_surcharge_tax2 = (bool)$order->custom_surcharge_tax2;
        $prop->custom_surcharge_tax3 = (bool)$order->custom_surcharge_tax3;
        $prop->custom_surcharge_tax4 = (bool)$order->custom_surcharge_tax4;
        $prop->last_sent_date = $order->last_sent_date ?: '';
        $prop->emails = $this->transformOrderEmails($order->emails());

        return $prop;
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

        return $invitations->map(function (OrderInvitation $invitation) {
            return (new OrderInvitationTransformable)->transformOrderInvitation($invitation);
        })->all();
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

        return $emails->map(function (Email $email) {
            return (new EmailTransformable())->transformEmail($email);
        })->all();
    }
}
