<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LessonFilter\FilterResource;
use App\Models\Group;
use App\Models\Location;
use App\Models\User;

class LessonFilterController extends Controller
{
    public function __invoke()
    {
        $groups = Group::all();
        $teachers = User::onlyTeachers()->get();
        $locations = Location::all();

        $filters['groups'] = $groups;
        $filters['teachers'] = $teachers;
        $filters['locations'] = $locations;

        return new FilterResource($filters);
    }
}
