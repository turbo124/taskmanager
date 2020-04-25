<?php

namespace App\Transformations;

use App\Email;

class EmailTransformable
{

    /**
     * @param Email $email
     * @return Email
     */
    public function transformEmail(Email $email)
    {
        $prop = new Email;
        $prop->id = (int)$email->id;
        $prop->entity = $email->entity;
        $prop->entity_id = (int)$email->entity_id;
        $prop->subject = (string)$email->subject;
        $prop->body = $email->body;
        $prop->recipient = $email->recipient ?: '';
        $prop->recipient_email = $email->recipient_email ?: '';
        $prop->sent_at = $email->sent_at ?: '';
        $prop->design = $email->design ?: '';
        $prop->updated_at = $email->updated_at;
        $prop->archived_at = $email->deleted_at;

        return $prop;
    }

}
