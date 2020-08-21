<?php

namespace App\Requests\Subscription;

use App\Repositories\Base\BaseFormRequest;

class UpdateSubscriptionRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target_url' => 'required|url',
            'event_id'   => 'required',
        ];
    }
}
