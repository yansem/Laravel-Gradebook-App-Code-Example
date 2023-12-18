<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'last_name' => ['required', 'alpha', 'min:2', 'max:255'],
            'first_name' => ['required', 'alpha', 'min:2', 'max:255'],
            'patronymic' => ['required', 'alpha', 'min:2', 'max:255'],
            'login' => ['required', 'string', 'min:5', 'max:255', Rule::unique('users')->ignore($this->id)],
            'password' => ['required', 'string', 'min:5', 'max:255'],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
        ];
    }

    public function attributes()
    {
        return [
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'patronymic' => 'Отчество',
            'login' => 'Логин',
            'password' => 'Пароль',
            'role_id' => 'Роль'
        ];
    }
}
