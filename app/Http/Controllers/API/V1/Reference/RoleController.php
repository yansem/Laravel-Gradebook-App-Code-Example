<?php

namespace App\Http\Controllers\API\V1\Reference;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RoleResource;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function __invoke()
    {
        Gate::authorize('show-roles');
        return RoleResource::collection(Role::all());
    }
}
