<?php

namespace App\Http\Resources\V1\Lesson;

use App\Http\Resources\V1\GradeResource;
use App\Http\Resources\V1\WorkResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonGradesResource extends JsonResource
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
            'works' => WorkResource::collection($this['works']),
            'grades' => GradeResource::collection($this['grades'])
        ];
    }
}
