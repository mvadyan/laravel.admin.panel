<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminGroupFilterAddRequest extends FormRequest
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
            'title' => 'min:4|max:25'
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'title.min' => 'Минимальная длина 4 символа',
            'title.max' => 'Максимальная длина 25 символов',
        ];
    }
}
