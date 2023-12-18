<?php

namespace App\Http\Resources\V1\Lesson;

use App\Http\Resources\V1\GroupResource;
use App\Http\Resources\V1\LocationResource;
use App\Http\Resources\V1\SubjectResource;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\WorkResource;
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
            'id' => $this->id,
            'title' => $this->title,
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'teacher' => new UserResource($this->whenLoaded('teacher')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'start' => $this->start,
            'end' => $this->end,
            'color' => $this->whenLoaded('work', function () {
                return $this->work->color;
            }),
            'duration_minutes' => $this->duration_minutes,
            'description' => $this->description,
            'groups' => GroupResource::collection($this->whenLoaded('groups')),
            'groups_id' => $this->whenLoaded('groups', function () {
                return $this->groups->pluck('id');
            }),
            'work' => new WorkResource($this->whenLoaded('work')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
