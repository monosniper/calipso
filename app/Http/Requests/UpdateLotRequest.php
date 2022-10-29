<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLotRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'max:350'],
            'description' => ['required', 'max:10000'],
            'price' => ['required', 'min:1'],
            'status' => ['sometimes'],
            'isPremium' => ['sometimes'],
//            'properties' => ['sometimes'],
//            'images' => ['required', 'max:10'],
//            'archive' => ['required'],
//            'images.*' => ['required', 'image'],
        ];
    }
}
