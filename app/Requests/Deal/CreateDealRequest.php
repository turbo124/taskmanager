<?php

namespace App\Requests\Deal;

use App\Repositories\Base\BaseFormRequest;

class CreateDealRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'      => ['required'],
            'valued_at'  => ['required'],
           
        ];
    }

}
