<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSafeTzRequest extends FormRequest
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
            'safe_id' => ['exists:safes,id'],
            'tz' => ['nullable', 'min:200', 'max:100000'],
            'files' => ['max:10'],
            'files.*' => ['max:100000']
        ];
    }
}
