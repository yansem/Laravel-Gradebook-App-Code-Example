<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\SettingRequest;
use App\Http\Resources\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index()
    {
        return new SettingResource(Setting::find(1));
    }

    public function update(SettingRequest $request)
    {
        Gate::authorize('edit-settings');
        Setting::first()->update($request->validated());
    }
}
