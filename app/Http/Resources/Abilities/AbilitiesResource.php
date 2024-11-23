<?php

namespace App\Http\Resources\Abilities;

use Illuminate\Http\Resources\Json\JsonResource;

class AbilitiesResource extends JsonResource
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
          'id'=>$this->id,
          'nombre'=>$this->name,
          'titulo'=>$this->title,
        ];
    }
}
