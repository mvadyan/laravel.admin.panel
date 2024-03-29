<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'title' => 'required|min:4|max:200',
            'slug' => 'max:200',
            'description' => 'string|min:3|max:500',
            'parent_id' => 'integer',
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'title.min' => 'Минимальное имяя 4 символа',
            'description.min' => 'Минимальная длина описания 5 символов',
            'description.string' => 'Описания долдно быть текстом',
        ];
    }
}
