<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class TeacherController extends Controller
{
    public function index()
    {
        Gate::authorize('show-teachers');
        $teachers = User::onlyTeachers()->orderBy('last_name')->get();
        return UserResource::collection($teachers);
    }
}
