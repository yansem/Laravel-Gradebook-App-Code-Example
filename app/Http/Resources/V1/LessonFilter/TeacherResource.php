<?php

namespace App\Http\Resources\V1\LessonFilter;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'last_name_initials' => $this->last_name_initials
        ];
    }
}
