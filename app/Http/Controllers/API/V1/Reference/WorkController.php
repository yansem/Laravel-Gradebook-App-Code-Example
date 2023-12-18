<?php

namespace App\Http\Controllers\API\V1\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\WorkRequest;
use App\Http\Resources\V1\WorkResource;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('show-references');
        if (!$request->page) {
            $works = Work::orderBy('title')->get();
        } else {
            $works = Work::withFilters(
                $request->id,
                $request->title,
                $request->description,
                $request->trashed
            )
                ->orderBy('title')
                ->paginate(self::PER_PAGE);
        }
        return WorkResource::collection($works);
    }

    public function store(WorkRequest $request)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        Work::create($data);
        return response('work created', 201);
    }

    public function update(WorkRequest $request, $id)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        $work = Work::findOrFail($id);
        $work->update($data);
        return response('work updated', 200);
    }

    public function destroy($id)
    {
        Gate::authorize('edit-references');
        $work = Work::findOrFail($id);
        $work->delete();
        return response('work deleted', 200);
    }

    public function restore($id)
    {
        Gate::authorize('edit-references');
        $grade = Work::withTrashed()->findOrFail($id);
        $grade->restore();
        return response('work restored', 200);
    }
}
