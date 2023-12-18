<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('users/restore/{id}', [\App\Http\Controllers\API\V1\UserController::class, 'restore']);
    Route::apiResource('users', \App\Http\Controllers\API\V1\UserController::class);

    Route::group(['prefix' => 'references'], function () {
        Route::apiResources([
            'groups' => \App\Http\Controllers\API\V1\Reference\GroupController::class,
            'subjects' => \App\Http\Controllers\API\V1\Reference\SubjectController::class,
            'locations' => \App\Http\Controllers\API\V1\Reference\LocationController::class,
            'grades' => \App\Http\Controllers\API\V1\Reference\GradeController::class,
            'works' => \App\Http\Controllers\API\V1\Reference\WorkController::class,
        ], ['except' => ['show']]);
        Route::get('/subjects/restore/{id}', [\App\Http\Controllers\API\V1\Reference\SubjectController::class, 'restore']);
        Route::get('/groups/restore/{id}', [\App\Http\Controllers\API\V1\Reference\GroupController::class, 'restore']);
        Route::get('/locations/restore/{id}', [\App\Http\Controllers\API\V1\Reference\LocationController::class, 'restore']);
        Route::get('/grades/restore/{id}', [\App\Http\Controllers\API\V1\Reference\GradeController::class, 'restore']);
        Route::get('/works/restore/{id}', [\App\Http\Controllers\API\V1\Reference\WorkController::class, 'restore']);
        Route::get('/roles', \App\Http\Controllers\API\V1\Reference\RoleController::class);
    });
    Route::get('/references_general', \App\Http\Controllers\API\V1\LessonGeneralController::class);
    Route::get('/references_grades', \App\Http\Controllers\API\V1\LessonGradesController::class);
    Route::get('/lesson_filters', \App\Http\Controllers\API\V1\LessonFilterController::class);
    Route::group(['prefix' => 'lessons'], function () {
        Route::get('/{id}/visits', [\App\Http\Controllers\API\V1\UserVisitController::class, 'show']);
        Route::patch('/{id}/visits', [\App\Http\Controllers\API\V1\UserVisitController::class, 'update']);
        Route::get('/{id}/grades', [\App\Http\Controllers\API\V1\GradeUserController::class, 'show']);
        Route::patch('/{id}/grades', [\App\Http\Controllers\API\V1\GradeUserController::class, 'update']);
        Route::get('/restore/{id}', [\App\Http\Controllers\API\V1\LessonController::class, 'restore']);
    });
    Route::apiResource('lessons', \App\Http\Controllers\API\V1\LessonController::class);
    Route::get('/teachers', [\App\Http\Controllers\API\V1\TeacherController::class, 'index']);
    Route::get('/students', [\App\Http\Controllers\API\V1\StudentController::class, 'index']);
    Route::get('/profile', [\App\Http\Controllers\API\V1\ProfileController::class, 'index']);
    Route::get('/settings', [\App\Http\Controllers\API\V1\SettingController::class, 'index']);
    Route::patch('/settings', [\App\Http\Controllers\API\V1\SettingController::class, 'update']);
});
