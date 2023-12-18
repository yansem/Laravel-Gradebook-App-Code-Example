<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\UserStoreRequest;
use App\Http\Requests\API\V1\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('edit-users'); //todo инструктор при редактировании справочника групп не получит users
        if (!$request->page) {
            $users = User::orderBy('last_name')->get();
        } else {
            $users = User::withFilters(
                $request->id,
                $request->last_name,
                $request->first_name,
                $request->patronymic,
                $request->login,
                $request->role,
                $request->trashed
            )
                ->with('role')
                ->orderBy('last_name')
                ->paginate(self::PER_PAGE);
        }
        return UserResource::collection($users);
    }

    public function store(UserStoreRequest $request)
    {
        Gate::authorize('edit-users');
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['api_token'] = Str::random(80);
        User::create($data);
        return response('user created', 201);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        Gate::authorize('edit-users');
        $data = $request->validated();
        $user = User::findOrFail($id);
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        $user->update($data);
        return response('user updated', 200);
    }

    public function destroy($id)
    {
        Gate::authorize('edit-users');
        $user = User::findOrFail($id);
        $user->delete();
        return response('user deleted', 200);
    }

    public function restore($id)
    {
        Gate::authorize('edit-users');
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return response('user restored', 200);
    }
}
