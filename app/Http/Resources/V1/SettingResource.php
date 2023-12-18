<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public $preserveKeys = true;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'min_time' => $this->min_time,
            'max_time' => $this->max_time,
            'lesson_max_duration' => $this->lesson_max_duration,
            'slot_duration' => $this->slot_duration,
            'slot_label_duration' => $this->slot_label_duration
        ];

    }
}
