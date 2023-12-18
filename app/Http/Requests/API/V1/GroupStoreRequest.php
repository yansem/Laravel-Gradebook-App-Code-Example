<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class GroupStoreRequest extends FormRequest
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
            'title' => 'required|string|min:5|max:100',
            'description' => ['nullable', 'string', 'max:65535'],
            'date_start' => 'required|string|before:date_end',
            'date_end' => 'required|string|after:date_start',
            'students_id' => 'nullable|array',
            'students_id.*' => 'exists:users,id'
        ];
    }

    public function attributes()
    {
        return [
            'date_start' => 'Дата начала',
            'date_end' => 'Дата окончания',
            'students_id' => 'Студенты',
            'students_id.*' => 'Студенты'
        ];
    }

    public function messages()
    {
        return [
            'date_start.required' => 'Поле не заполнено или введена недействительная дата',
            'date_end.required' => 'Поле не заполнено или введена недействительная дата',
        ];
    }
}
