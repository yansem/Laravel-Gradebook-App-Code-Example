<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:5', 'max:100', Rule::unique('works')->ignore($this->id)],
            'description' => ['nullable', 'string', 'max:65535'],
            'color' => 'required|regex:/^#[a-zA-Z0-9]{6}/'
        ];
    }

    public function attributes()
    {
        return [
            'color' => 'Цвет'
        ];
    }
}
