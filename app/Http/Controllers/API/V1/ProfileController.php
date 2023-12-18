<?php

namespace App\Http\Controllers\API\V1;

use App\Exports\StudentExport;
use App\Exports\TeacherExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Profile\Student\GroupResource;
use App\Http\Resources\V1\Profile\Teacher\SubjectResource;
use App\Services\ProfileService;
use Maatwebsite\Excel\Facades\Excel;

class ProfileController extends Controller
{
    public function index(ProfileService $service)
    {
        if (auth()->user()->isTeacher()) {
            $subjects = $service->createArrayForTeacher();
            return SubjectResource::collection($subjects);
        } elseif (auth()->user()->isStudent()) {
            $groups = $service->createArrayForStudent();
            return GroupResource::collection($groups);
        }
        return true;
    }

    public function export(ProfileService $service)
    {
        if (auth()->user()->isTeacher()) {
            $subjects = $service->createArrayForTeacher();
            return Excel::download(new TeacherExport($subjects), 'profile.xlsx');
        } elseif (auth()->user()->isStudent()) {
            $groups = $service->createArrayForStudent();
            return Excel::download(new StudentExport($groups), 'profile.xlsx');
        }
        return true;
    }
}
