<?php

namespace App\Http\Resources\V1\Profile\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
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
            'visited' => $this['visited'] ? '+' : 'Н',
            'comment' => $this['comment'] ? '(' . $this['comment'] . ')' : ''
        ];
    }
}
