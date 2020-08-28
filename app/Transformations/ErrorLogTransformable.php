
<?php

namespace App\Transformations;

use App\Models\Email;

class ErrorLogTransformable
{

    /**
     * @param Email $email
     * @return array
     */
    public function transformErrorLog(ErrorLog $error_log)
    {
        return [
            'id'              => (int)$error_log->id,
            'entity'          => $error_log->entity,
            'entity_id'       => (int)$error_log->entity_id,
            'data'            => $error_log->data,
            'account_id'      => (int)$error_log->account_id,
            'user_id'         => (int)$error_log->user_id,
            'customer_id'     => (int)$error_log->customer_id,
            'created_at'      => $error_log->created_at ?: 
            'design'          => $email->design ?: '',
            'updated_at'      => $email->updated_at,
            'archived_at'     => $email->deleted_at,
        ];
    }

}
