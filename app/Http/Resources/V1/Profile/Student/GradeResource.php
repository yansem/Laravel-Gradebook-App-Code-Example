<?php

namespace App\Http\Resources\V1\Profile\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'grade_value' => $this['grade_value'],
            'comment' => $this['comment'] ? '(' . $this['comment'] . ')' : '',
            'work_title' => $this['work_title']
        ];
    }
}
