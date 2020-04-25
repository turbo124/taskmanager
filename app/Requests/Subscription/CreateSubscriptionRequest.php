<?php

namespace App\Requests\Subscription;

use App\Repositories\Base\BaseFormRequest;
use App\Settings;

class CreateSubscriptionRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target_url' => 'required',
            'event_id'   => 'required',
        ];
    }
}
