<?php

namespace App\Http\Controllers\API\V1\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\GradeRequest;
use App\Http\Resources\V1\GradeResource;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('show-references');
        if (!$request->page) {
            $grades = Grade::orderBy('value')->get();
        } else {
            $grades = Grade::withFilters(
                $request->id,
                $request->title,
                $request->value,
                $request->description,
                $request->trashed
            )
                ->orderBy('value')
                ->paginate(self::PER_PAGE);
        }
        return GradeResource::collection($grades);
    }

    public function store(GradeRequest $request)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        Grade::create($data);
        return response('grade created', 201);
    }

    public function update(GradeRequest $request, $id)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        $grade = Grade::findOrFail($id);
        $grade->update($data);
        return response('grade updated', 200);

    }

    public function destroy($id)
    {
        Gate::authorize('edit-references');
        $grade = Grade::findOrFail($id);
        $grade->delete();
        return response('grade deleted', 200);
    }

    public function restore($id)
    {
        Gate::authorize('edit-references');
        $grade = Grade::withTrashed()->findOrFail($id);
        $grade->restore();
        return response('grade restored', 200);
    }
}
