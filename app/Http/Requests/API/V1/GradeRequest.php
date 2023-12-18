<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeRequest extends FormRequest
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
            'value' => ['required', 'integer', 'numeric', 'gt:0', Rule::unique('grades')->ignore($this->id)],
            'title' => ['required', 'string', 'min:5', 'max:20', Rule::unique('grades')->ignore($this->id)],
            'description' => ['nullable', 'string', 'max:65535']
        ];
    }

    public function attributes()
    {
        return [
            'value' => 'Значение'
        ];
    }
}
