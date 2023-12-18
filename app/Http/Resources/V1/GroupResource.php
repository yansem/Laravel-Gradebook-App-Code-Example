<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'students' => UserResource::collection($this->whenLoaded('students')),
            'students_id' => $this->whenLoaded('students', function () {
                return $this->students->pluck('id');
            }),
            'date_start' => Carbon::parse($this->date_start)->format('d.m.Y'),
            'date_end' => Carbon::parse($this->date_end)->format('d.m.Y'),
            'lessons_count' => $this->lessons_count,
            'deleted_at' => $this->deleted_at
        ];
    }
}
