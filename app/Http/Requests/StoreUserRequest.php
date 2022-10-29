<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'username' => ['unique:users', 'max:255'],
            'password' => ['confirmed', 'max:32', 'min:8'],
            'email' => ['required', 'unique:users', 'email'],
        ];
    }
}
