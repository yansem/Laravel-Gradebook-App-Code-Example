<?php

namespace App\Http\Resources\V1\Lesson;

use App\Http\Resources\V1\GroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeUserResource extends JsonResource
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
            'groups' => GroupResource::collection($this->groups),
            'students_grades' => $this->students_grades,
            'work_id' => $this->work->id
        ];
    }
}
