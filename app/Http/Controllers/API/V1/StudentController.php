<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('show-students');
        $students = User::onlyStudents()->orderBy('last_name')->get();
        return UserResource::collection($students);
    }
}
