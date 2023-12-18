<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Lesson\LessonGradesResource;
use App\Models\Grade;
use App\Models\Work;
use Illuminate\Support\Facades\Gate;

class LessonGradesController extends Controller
{
    public function __invoke()
    {
        Gate::authorize('show-references');
        $works = Work::all();
        $grades = Grade::all();

        $modal['works'] = $works;
        $modal['grades'] = $grades;

        return new LessonGradesResource($modal);
    }
}
