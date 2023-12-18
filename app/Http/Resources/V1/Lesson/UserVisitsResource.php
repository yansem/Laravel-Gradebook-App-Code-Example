<?php

namespace App\Http\Resources\V1\Lesson;

use App\Http\Resources\V1\GroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVisitsResource extends JsonResource
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
            'students_visits' => $this->students_visits
        ];
    }
}
