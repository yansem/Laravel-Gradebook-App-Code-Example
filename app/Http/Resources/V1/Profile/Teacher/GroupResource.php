<?php

namespace App\Http\Resources\V1\Profile\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'id' => $this['id'],
            'title' => $this['title'],
            'lessons' => LessonResource::collection($this['lessons']),
            'gradeAvg' => $this['gradeAvg'],
            'visitPercent' => $this['visitPercent']
        ];
    }
}
