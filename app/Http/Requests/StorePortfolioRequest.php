<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePortfolioRequest extends FormRequest
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
            'title' => ['required', 'min:3', 'max:200'],
            'link' => ['url', 'nullable'],
            'description' => ['required', 'min:10', 'max:500'],
            'preview' => ['required', 'image'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Поле название необходимо к заполнению',
            'link.required' => 'Поле ссылка необходимо к заполнению',
            'description.required' => 'Поле описание необходимо к заполнению',
            'preview.required' => 'Поле картинка необходимо к заполнению',

            'link.url' => 'Поле ссылка должно быть вида url',

            'title.min' => 'Поле название должно содержать не менее 10 символов',
            'title.max' => 'Поле название должно содержать не более 200 символов',
            'description.min' => 'Поле описание должно содержать не менее 10 символов',
            'description.max' => 'Поле описание должно содержать не более 500 символов',

            'preview.image' => 'Поле картинка должно быть с расширением jpg, jpeg, png, bmp, gif, svg, или webp',
        ];
    }
}
