<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Lesson\LessonGeneralResource;
use App\Models\Group;
use App\Models\Location;
use App\Models\Subject;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Gate;

class LessonGeneralController extends Controller
{
    public function __invoke()
    {
        Gate::authorize('show-references');
        $groups = Group::all();
        $teachers = User::onlyTeachers()->get();
        $locations = Location::all();
        $subjects = Subject::all();
        $works = Work::all();

        $modal['groups'] = $groups;
        $modal['teachers'] = $teachers;
        $modal['locations'] = $locations;
        $modal['subjects'] = $subjects;
        $modal['works'] = $works;

        return new LessonGeneralResource($modal);
    }
}
