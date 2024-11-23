<?php

namespace App\Http\Resources\Roles;

use App\Http\Resources\Abilities\AbilitiesCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return  [
            'id'=>$this->id,
            'nombre'=>$this->name,
            'titulo'=>$this->title,
            'relaciones'=>[
                'habilidades'=> new AbilitiesCollection($this->abilities)
            ]
        ];
    }
}
