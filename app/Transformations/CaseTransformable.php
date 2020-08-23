<?php

namespace App\Transformations;


use App\Models\Cases;
use App\Models\Email;
use App\Models\File;
use App\Models\Subscription;

trait CaseTransformable
{
    /**
     * @param Subscription $subscription
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
            'assigned_to'   => (int)$cases->assigned_to,
            'parent_id'     => (int)$cases->parent_id,
            'status_id'     => (int)$cases->status_id,
            'category_id'   => (int)$cases->category_id,
            'priority_id'   => (int)$cases->priority_id,
            'files'         => $this->transformCaseFiles($cases->files),
            'emails'        => $this->transformCaseEmails($cases->emails()),
            'updated_at'    => $cases->updated_at,
            'created_at'    => $cases->created_at,
            'is_deleted'    => (bool)$cases->is_deleted,
            'custom_value1' => (string)$cases->custom_value1 ?: '',
            'custom_value2' => (string)$cases->custom_value2 ?: '',
            'custom_value3' => (string)$cases->custom_value3 ?: '',
            'custom_value4' => (string)$cases->custom_value4 ?: '',
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

    private function transformCaseEmails($emails)
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
