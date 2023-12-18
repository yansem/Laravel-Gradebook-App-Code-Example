<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'last_name_initials' => $this->last_name_initials,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'patronymic' => $this->patronymic,
            'login' => $this->login,
            'role' => new RoleResource($this->whenLoaded('role')),
            'role_id' => $this->role_id,
            'group_ids' => $this->when($this->relationLoaded('groups'), function () {
                return $this->groups->pluck('id');
            }),
            'lessons_count' => $this->when($this->lessons_count !== null, function () {
                return $this->lessons_count;
            }),
            'deleted_at' => $this->deleted_at
        ];
    }
}
