<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'photo' => url($this->image),
            'owner' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'family' => [
                'name' => $this->family->name,
            ],
            'created_at' => $this->created_at
        ];
    }
}
