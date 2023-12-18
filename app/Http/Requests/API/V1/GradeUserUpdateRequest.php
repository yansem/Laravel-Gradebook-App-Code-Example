<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeUserUpdateRequest extends FormRequest
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
            'students_grades' => 'nullable|array',
            'students_grades.*.lesson_id' => [Rule::requiredIf(!empty($this->students_grades)), 'integer', 'exists:lessons,id'],
            'students_grades.*.user_id' => [Rule::requiredIf(!empty($this->students_grades)), 'integer', 'exists:users,id'],
            'students_grades.*.group_id' => [Rule::requiredIf(!empty($this->students_grades)), 'integer', 'exists:groups,id'],
            'students_grades.*.work_id' => [Rule::requiredIf(!empty($this->students_grades)), 'integer', 'exists:works,id'],
            'students_grades.*.grade_id' => [Rule::requiredIf(!empty($this->students_grades)), 'integer', 'exists:grades,id'],
            'students_grades.*.comment' => 'nullable|string|max:100'
        ];
    }

    public function attributes()
    {
        return [
            'students_grades' => 'Оценки',
            'students_grades.*.lesson_id' => 'Занятие',
            'students_grades.*.user_id' => 'Студент',
            'students_grades.*.work_id' => 'Тип занятия',
            'students_grades.*.grade_id' => 'Оценка',
            'students_grades.*.comment' => 'Комментарий'
        ];
    }
}
