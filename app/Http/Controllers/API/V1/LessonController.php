<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\LessonStoreRequest;
use App\Http\Requests\API\V1\LessonUpdateRequest;
use App\Http\Resources\V1\Lesson\LessonResource;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::withFilters(
            request()->input('lessons_start'),
            request()->input('lessons_end'),
            request()->input('groups', []),
            request()->input('teachers', []),
            request()->input('locations', [])
        )->with('location', 'subject', 'groups.students', 'teacher', 'work')->withTrashed()->get();
        return LessonResource::collection($lessons);
    }

    public function store(LessonStoreRequest $request)
    {
        Gate::authorize('edit-lessons');
        $data = $request->validated();
        $groups_id = $data['groups_id'];
        $data['end'] = Carbon::parse($data['start'])->addMinutes($data['duration_minutes']);
        $lesson = Lesson::create($data);
        if (!empty($groups_id)) {
            $lesson->groups()->attach($groups_id);
        }
        return response('lesson created', 201);
    }

    public function show($id)
    {
        $lesson = Lesson::with('location', 'subject', 'groups.students', 'teacher', 'work')->findOrFail($id);
        return new LessonResource($lesson);
    }

    public function update(LessonUpdateRequest $request, $id)
    {
        Gate::authorize('edit-lessons');
        $data = $request->validated();
        $lesson = Lesson::with('location', 'subject', 'groups.students', 'teacher', 'work')->findOrFail($id);
        $groups_id = $data['groups_id'];
        $data['end'] = Carbon::parse($data['start'])->addMinutes($data['duration_minutes']);
        $lesson->update($data);
        $lesson->groups()->sync($groups_id);
        $lesson = $lesson->fresh();
        $lesson->load('location', 'subject', 'groups.students', 'teacher', 'work');

        return new LessonResource($lesson);
    }

    public function destroy($id)
    {
        Gate::authorize('edit-lessons');
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();
        return response('lesson deleted', 200);
    }

    public function restore($id)
    {
        Gate::authorize('edit-lessons');
        $lesson = Lesson::withTrashed()->findOrFail($id);
        $lesson->restore();
        return response('lesson restored', 200);
    }
}
