<?php

namespace App\Transformations;


use App\Models\Cases;
use App\Models\File;
use App\Models\Subscription;

trait CaseTransformable
{
    /**
     * @param \App\Models\Subscription $subscription
     * @return array
     */
    public function transform(Cases $cases)
    {
        $customer = $cases->customer;

        return [
            'id'            => (int)$cases->id,
            'number'        => $cases->number ?: '',
            'message'       => $cases->message,
            'subject'       => $cases->subject,
            'private_notes' => $cases->private_notes,
            'due_date'      => $cases->due_date,
            'account_id'    => (int)$cases->account_id,
            'customer_id'   => (int)$cases->customer_id,
            'customer_name' => $customer->name,
            'user_id'       => (int)$cases->user_id,
            'status_id'     => (int)$cases->status_id,
            'category_id'   => (int)$cases->category_id,
            'priority_id'   => (int)$cases->priority_id,
            'files'         => $this->transformCaseFiles($cases->files),
            'updated_at'    => $cases->updated_at,
            'created_at'    => $cases->created_at,
            'is_deleted'    => (bool)$cases->is_deleted
        ];
    }

    /**
     * @param $files
     * @return array
     */
    private function transformCaseFiles($files)
    {
        if (empty($files)) {
            return [];
        }

        return $files->map(
            function (File $file) {
                return (new FileTransformable())->transformFile($file);
            }
        )->all();
    }
}
