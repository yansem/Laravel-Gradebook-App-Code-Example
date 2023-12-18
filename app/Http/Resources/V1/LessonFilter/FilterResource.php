<?php

namespace App\Http\Resources\V1\LessonFilter;

use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
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
            'teachers' => TeacherResource::collection($this['teachers']),
            'locations' => LocationResource::collection($this['locations'])
        ];
    }
}
