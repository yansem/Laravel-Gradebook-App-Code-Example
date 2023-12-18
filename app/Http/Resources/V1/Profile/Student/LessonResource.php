<?php

namespace App\Http\Resources\V1\Profile\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'start' => $this['start'],
            'grades' => !empty($this['grades']) ? GradeResource::collection($this['grades']) : $this['grades'],
            'visit' => !empty($this['visit']) ? new VisitResource($this['visit']) : $this['visit']
        ];
    }
}
