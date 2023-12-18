<?php

namespace App\Http\Controllers\API\V1\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\SubjectRequest;
use App\Http\Resources\V1\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('show-references');
        if (!$request->page) {
            $subjects = Subject::orderBy('title')->get();
        } else {
            $subjects = Subject::withFilters(
                $request->id,
                $request->title,
                $request->description,
                $request->trashed
            )
                ->orderBy('title')
                ->paginate(self::PER_PAGE);
        }
        return SubjectResource::collection($subjects);
    }

    public function store(SubjectRequest $request)
    {
        $data = $request->validated();
        Subject::create($data);
        return response('subject created', 201);
    }

    public function show($id)
    {
        $subject = Subject::findOrFail($id);
        return new SubjectResource($subject);

    }

    public function update(SubjectRequest $request, $id)
    {
        $data = $request->validated();
        $subject = Subject::findOrFail($id);
        $subject->update($data);
        return response('subject updated', 200);

    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response('subject deleted', 200);
    }

    public function restore($id)
    {
        $subject = Subject::withTrashed()->findOrFail($id);
        $subject->restore();
        return response('subject restored', 200);
    }
}
