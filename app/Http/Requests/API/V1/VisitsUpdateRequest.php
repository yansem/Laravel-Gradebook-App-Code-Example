<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitsUpdateRequest extends FormRequest
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
            'students_visits' => 'nullable|array',
            'students_visits.*.lesson_id' => [Rule::requiredIf(!empty($this->students_visits)), 'integer', 'exists:lessons,id'],
            'students_visits.*.user_id' => [Rule::requiredIf(!empty($this->students_visits)), 'integer', 'exists:users,id'],
            'students_visits.*.group_id' => [Rule::requiredIf(!empty($this->students_visits)), 'integer', 'exists:groups,id'],
            'students_visits.*.visited' => [Rule::requiredIf(!empty($this->students_visits)), 'boolean'],
            'students_visits.*.comment' => 'nullable|string|max:100'
        ];
    }

    public function attributes()
    {
        return [
            'students_visits' => 'Посещения',
            'students_visits.*.lesson_id' => 'Занятие',
            'students_visits.*.user_id' => 'Студент',
            'students_visits.*.visited' => 'Посещение',
            'students_visits.*.comment' => 'Комментарий'
        ];
    }
}
