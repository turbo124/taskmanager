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
            'source_type'  => 'nullable|numeric',
            'rating'       => 'nullable|numeric',
            'customer_id'  => 'nullable|numeric',
            //'task_type' => 'required',
            'title'        => 'required',
            'description'      => 'required',
           
            'start_date'   => 'nullable',
            //'task_status' => 'required',
       
        ];
    
    }

}
