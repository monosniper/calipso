<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['max:255'],
            'last_name' => ['max:255'],
            'location' => ['max:255'],
            'roles.*' => ['exists:roles,name'],
            'username' => ['required', 'max:255',
//                Rule::unique('users')->ignore(auth()->id())
            ],
            'password' => ['confirmed', 'max:32', 'min:8'],
            'categories' => ['max:2'],
            'email' => ['required', 'email',
//                Rule::unique('users')->ignore(auth()->id())
            ],
        ];
    }
}
