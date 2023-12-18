<?php

namespace App\Http\Resources\V1\Profile\Teacher;

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
            'id' => $this['id'],
            'title' => $this['title'],
            'groups' => isset($this['groups']) ? GroupResource::collection($this['groups']) : []
        ];
    }
}
