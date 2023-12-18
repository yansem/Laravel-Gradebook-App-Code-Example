<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\VisitsUpdateRequest;
use App\Http\Resources\V1\Lesson\UserVisitsResource;
use App\Models\Lesson;
use Illuminate\Support\Facades\Gate;

class UserVisitController extends Controller
{
    public function show($id)
    {
        Gate::authorize('edit-lessons');
        $lesson = Lesson::with('groups.students', 'students_visits')->findOrFail($id);
        foreach ($lesson['groups'] as $index => $group) {
            if ($group['deleted_at'] !== null) {
                $lesson['groups']->forget($index);
            }
        }
        return new UserVisitsResource($lesson);
    }

    public function update(VisitsUpdateRequest $request, $id)
    {
        Gate::authorize('edit-lessons');
        $data = $request->validated();
        $lesson = Lesson::findOrFail($id);
        $students_visits = $data['students_visits'];
        $lesson->students_visits()->detach();
        if (!empty($students_visits)) {
            foreach ($students_visits as $student_visit) {
                $lesson->students_visits()->attach($student_visit['user_id'], [
                    'group_id' => $student_visit['group_id'],
                    'visited' => $student_visit['visited'],
                    'comment' => $student_visit['comment'],
                ]);
            }
        }

    }
}
