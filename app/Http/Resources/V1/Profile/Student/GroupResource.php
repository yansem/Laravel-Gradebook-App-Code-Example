<?php

namespace App\Http\Resources\V1\Profile\Student;

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
            'subjects' => isset($this['subjects']) ? SubjectResource::collection($this['subjects']) : [],
        ];
    }
}
