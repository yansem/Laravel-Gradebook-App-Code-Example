<?php

namespace App\Http\Resources\V1\Lesson;

use App\Http\Resources\V1\GroupResource;
use App\Http\Resources\V1\LocationResource;
use App\Http\Resources\V1\SubjectResource;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\WorkResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonGeneralResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'groups' => GroupResource::collection($this['groups']),
            'teachers' => UserResource::collection($this['teachers']),
            'locations' => LocationResource::collection($this['locations']),
            'subjects' => SubjectResource::collection($this['subjects']),
            'works' => WorkResource::collection($this['works'])
        ];
    }
}
