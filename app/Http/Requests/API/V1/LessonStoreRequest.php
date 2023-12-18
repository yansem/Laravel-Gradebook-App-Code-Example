<?php

namespace App\Http\Requests\API\V1;

use App\Helpers\Helper;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonStoreRequest extends FormRequest
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
        $dateAfter = Helper::getDateNow();
        $dateBefore = Helper::getDateAddYear();
        $startDate = Carbon::parse($this->start);
        $startTime = $startDate->format('H:i');
        $endDate = $startDate->copy()->addMinutes($this->duration_minutes);
        $endTime = $endDate->format('H:i');
        $settings = Setting::first();
        $slotMinTime = $settings->min_time;
        $slotMaxTime = $settings->max_time;
        $lessonMaxDuration = $settings->lesson_max_duration;

        return [
            'start' => [
                'required',
                'date_format:Y-m-d H:i',
                Rule::notIn(['Invalid date']),
                'after:' . $dateAfter,
                'before:' . $dateBefore,
                function ($attribute, $value, $fail) use ($startTime, $slotMinTime, $slotMaxTime) {
                    if ($startTime < $slotMinTime) {
                        return $fail('Время начала должно быть больше ' . $slotMinTime);
                    }
                    if ($startTime > $slotMaxTime) {
                        return $fail('Время начала должно быть меньше ' . $slotMaxTime);
                    }
                },
            ],
            'duration_minutes' => [
                'required',
                'integer',
                'numeric',
                'gt:0',
                'max:' . $lessonMaxDuration,
                function ($attribute, $value, $fail) use ($endTime, $slotMaxTime, $startDate, $endDate) {
                    if ($endTime > $slotMaxTime) {
                        return $fail('Время окончания не должно быть больше ' . $slotMaxTime);
                    }
                    if (!$startDate->isSameDay($endDate)) {
                        return $fail('Занятие должно быть в течение одного дня');
                    }
                },
            ],
            'title' => ['required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:65535'],
            'teacher_id' => 'required|integer|exists:users,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'location_id' => 'required|integer|exists:locations,id',
            'work_id' => 'required|integer|exists:works,id',
            'groups_id' => 'nullable|array',
            'groups_id.*' => 'exists:groups,id'
        ];
    }

    public function attributes()
    {
        return [
            'start' => 'Дата и время начала',
            'duration_minutes' => 'Продолжительность',
            'teacher_id' => 'Преподаватель',
            'subject_id' => 'Предмет',
            'location_id' => 'Аудитория',
            'work_id' => 'Тип занятия',
            'groups_id' => 'Группы'
        ];
    }

    public function messages()
    {
        $dateAfter = Helper::getDateNow();
        $dateBefore = Helper::getDateAddYear();
        return [
            'start.required' => 'Поле не заполнено или введена недействительная дата',
            'start.date_format' => 'Поле Дата и время начала не соответствует формату дд.мм.гггг ч:м.',
            'start.after' => 'В поле Дата и время начала должна быть дата больше ' . $dateAfter,
            'start.before' => 'В поле Дата и время начала должна быть дата меньше ' . $dateBefore
        ];
    }
}
