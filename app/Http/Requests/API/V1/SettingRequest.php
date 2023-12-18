<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
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
            'min_time' => ['required', 'string', 'max:8', 'before:max_time'],
            'max_time' => ['required', 'string', 'max:8', 'after:min_time'],
            'lesson_max_duration' => ['required', 'required', 'integer', 'numeric', 'gt:0', 'max:65535'],
            'slot_duration' => ['required', 'string', 'max:8', Rule::notIn('00:00')],
            'slot_label_duration' => ['required', 'string', 'max:8', Rule::notIn('00:00')]
        ];
    }

    public function attributes()
    {
        return [
            'min_time' => 'Время начала занятий',
            'max_time' => 'Время окончания занятий',
            'lesson_max_duration' => 'Максимальная продолжительность занятия',
            'slot_duration' => 'Частота временных интервалов',
            'slot_label_duration' => 'Частота отображения временных интервалов'
        ];
    }

    public function messages()
    {
        return [
            'min_time.before' => 'Время начала занятий должно быть меньше времени окончания занятий.',
            'max_time.after' => 'Время окончания занятий должно быть больше времени начала занятий.',
            'slot_duration.not_in' => 'Частота временных интервалов должна быть больше 00:00',
            'slot_label_duration.not_in' => 'Частота отображения временных интервалов должна быть больше 00:00'
        ];
    }
}
