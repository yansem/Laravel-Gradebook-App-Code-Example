<?php

namespace App\Http\Controllers\API\V1\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\GroupStoreRequest;
use App\Http\Requests\API\V1\GroupUpdateRequest;
use App\Http\Resources\V1\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('show-references');
        if (!$request->page) {
            $groups = Group::orderBy('title')->get();
        } else {
            $groups = Group::withFilters(
                $request->id,
                $request->title,
                $request->description,
                $request->group_start,
                $request->group_end,
                $request->trashed
            )
                ->with('students')
                ->orderBy('title')
                ->paginate(self::PER_PAGE);
        }
        return GroupResource::collection($groups);
    }

    public function store(GroupStoreRequest $request)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        $group = Group::create($data);
        if (!empty($data['students_id'])) {
            $group->students()->attach($data['students_id']);
        }
        return response('group created', 201);
    }

    public function update(GroupUpdateRequest $request, $id)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        $group = Group::findOrFail($id);
        $group->students()->detach();
        if (!empty($data['students_id'])) {
            $group->students()->attach($data['students_id']);
        }
        $group->update($data);
        return response('group updated', 200);
    }

    public function destroy($id)
    {
        Gate::authorize('edit-references');
        $group = Group::findOrFail($id);
        $group->delete();
        return response('group deleted', 200);
    }

    public function restore($id)
    {
        Gate::authorize('edit-references');
        $group = Group::withTrashed()->findOrFail($id);
        $group->restore();
        return response('group restored', 200);
    }
}
