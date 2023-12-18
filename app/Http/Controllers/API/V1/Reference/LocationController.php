<?php

namespace App\Http\Controllers\API\V1\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\LocationRequest;
use App\Http\Resources\V1\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('show-references');
        if (!$request->page) {
            $locations = Location::orderBy('title')->get();
        } else {
            $locations = Location::withFilters(
                $request->id,
                $request->title,
                $request->description,
                $request->trashed
            )
                ->orderBy('title')
                ->paginate(self::PER_PAGE);
        }

        return LocationResource::collection($locations);
    }

    public function store(LocationRequest $request)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        Location::create($data);
        return response('location created', 201);
    }

    public function update(LocationRequest $request, $id)
    {
        Gate::authorize('edit-references');
        $data = $request->validated();
        $location = Location::findOrFail($id);
        $location->update($data);
        return response('location updated', 200);

    }

    public function destroy($id)
    {
        Gate::authorize('edit-references');
        $location = Location::findOrFail($id);
        $location->delete();
        return response('location deleted', 200);
    }

    public function restore($id)
    {
        Gate::authorize('edit-references');
        $location = Location::withTrashed()->findOrFail($id);
        $location->restore();
        return response('location restored', 200);
    }
}
