<?php

namespace App\Http\Controllers;

use App\Http\Resources\V1\UserResource;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke()
    {
        $user = new UserResource(User::with(['role', 'groups'])->find(auth()->id()));
        return view('index', compact('user'));
    }
}
