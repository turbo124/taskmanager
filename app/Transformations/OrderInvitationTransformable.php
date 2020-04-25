<?php

namespace App\Transformations;

use App\Invoice;
use App\InvoiceInvitation;
use App\OrderInvitation;

class OrderInvitationTransformable
{
    /**
     * @param InvoiceInvitation $invitation
     * @return InvoiceInvitation
     */
    public function transformOrderInvitation(OrderInvitation $invitation)
    {
        $prop = new OrderInvitation;

        $prop->id = (int)$invitation->id;
        $prop->client_contact_id = (int)$invitation->client_contact_id;
        $prop->customer_id = (int)$invitation->customer_id;
        $prop->key = $invitation->key;
        //$prop->link = $invitation->getLink() ?: '';
        $prop->sent_date = $invitation->sent_date ?: '';
        $prop->viewed_date = $invitation->viewed_date ?: '';
        $prop->opened_date = $invitation->opened_date ?: '';
        $prop->updated_at = $invitation->updated_at;
        $prop->archived_at = $invitation->deleted_at;

        return $prop;
    }
}
