<?php

namespace App\Requests\User;

use App\Repositories\Base\BaseFormRequest;
use App\User;
use App\Repositories\UserRepository;

class UpdateUserRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'department'      => 'nullable|numeric',
            'gender'          => 'nullable|string',
            'job_description' => 'nullable|string',
            'phone_number'    => 'nullable|string',
            'dob'             => 'nullable|string',
            'role'            => 'nullable|array',
            'username'        => 'required|string',
            'profile_photo'   => 'nullable|string',
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'email'           => [
                'required',
                \Illuminate\Validation\Rule::unique('users')->ignore($this->route('user_id'))
            ]
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required'   => 'Username is required!',
            'email.required'      => 'Email is required!',
            'first_name.required' => 'First Name is required!',
            'last_name.required'  => 'Last Name is required!'
        ];
    }

}
