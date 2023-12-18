<?php

namespace App\Http\Resources\V1\Profile\Teacher;

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
            'students' => !empty($this['students']) ? StudentResource::collection($this['students']) : $this['students']
        ];
    }
}
