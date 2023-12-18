<?php

namespace App\Http\Resources\V1\Profile\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'title' => $this['title'],
            'lessons' => LessonResource::collection($this['lessons']),
            'gradeAvg' => $this['gradeAvg'],
            'visitPercent' => $this['visitPercent']
        ];
    }
}
