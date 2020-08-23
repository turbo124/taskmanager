<?php

namespace App\Transformations;

use App\Libraries\Utils;
use App\Models\Deal;

trait DealTransformable
{

    /**
     * @param Deal $deal
     * @return array
     */
    protected function transformDeal(Deal $deal)
    {
        return [
            'id'            => (int)$deal->id,
            'title'         => $deal->title,
            'description'   => $deal->description,
            'comments'      => $deal->comments,
            'due_date'      => $deal->due_date,
            'task_status'   => (int)$deal->task_status,
            'deleted_at'    => $deal->deleted_at,
            'rating'        => $deal->rating,
            'customer_id'   => $deal->customer_id,
            'valued_at'     => $deal->valued_at,
            'source_type'   => $deal->source_type,
            'is_deleted'    => (bool)$deal->is_deleted,
            'custom_value1' => $deal->custom_value1 ?: '',
            'custom_value2' => $deal->custom_value2 ?: '',
            'custom_value3' => $deal->custom_value3 ?: '',
            'custom_value4' => $deal->custom_value4 ?: '',
            'public_notes'  => $deal->public_notes ?: '',
            'private_notes' => $deal->private_notes ?: '',
            'files'         => $this->transformDealFiles($deal->files),
        ];
    }

    private function transformDealFiles($files)
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
