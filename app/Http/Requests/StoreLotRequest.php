<?php

namespace App\Http\Requests;

use App\Models\Lot;
use Illuminate\Foundation\Http\FormRequest;

class StoreLotRequest extends FormRequest
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
            'title' => ['required', 'min:10', 'max:350'],
            'description' => ['required', 'max:10000'],
            'price' => ['required', 'min:1'],
//            'archive' => ['required', 'file', 'mimes:rar,zip,7z', 'max:'.(string) (2 * 1024 * 1024)],
            'images' => ['required', 'max:10'],
            'archive' => ['required'],
            'status' => ['sometimes'],
            'isPremium' => ['sometimes'],
//            'properties' => ['sometimes'],
//            'images.*' => ['required', 'image'],
        ];
    }
}
