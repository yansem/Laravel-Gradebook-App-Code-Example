<?php

namespace App\Http\Resources\V1\Profile\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'last_name' => $this['last_name'],
            'first_name' => $this['first_name'],
            'patronymic' => $this['patronymic'],
            'grades' => !empty($this['grades']) ? GradeResource::collection($this['grades']) : $this['grades'],
            'visit' => !empty($this['visit']) ? new VisitResource($this['visit']) : $this['visit']
        ];
    }
}
