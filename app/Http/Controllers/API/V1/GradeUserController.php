<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\GradeUserUpdateRequest;
use App\Http\Resources\V1\Lesson\GradeUserResource;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


class GradeUserController extends Controller
{
    public function show($id)
    {
        Gate::authorize('edit-lessons');
        $lesson = Lesson::with('groups.students', 'students_grades', 'work')->findOrFail($id);
        foreach ($lesson['groups'] as $index => $group) {
            if ($group['deleted_at'] !== null) {
                $lesson['groups']->forget($index);
            }
        }
        return new GradeUserResource($lesson);
    }

    public function update(GradeUserUpdateRequest $request, $id)
    {
        Gate::authorize('edit-lessons');
        $data = $request->validated();
        $lesson = Lesson::findOrFail($id);
        $students_grades = $data['students_grades'];
        $lesson->students_grades()->detach();
        if (!empty($students_grades)) {
            foreach ($students_grades as $student_grade) {
                $lesson->students_grades()->attach($student_grade['user_id'], [
                    'group_id' => $student_grade['group_id'],
                    'work_id' => $student_grade['work_id'],
                    'grade_id' => $student_grade['grade_id'],
                    'comment' => $student_grade['comment'],
                    'date' => Carbon::now()->toDateTimeString()
                ]);
            }
        }
    }
}
