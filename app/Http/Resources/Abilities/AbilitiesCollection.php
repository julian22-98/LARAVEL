<?php

namespace App\Http\Resources\Abilities;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AbilitiesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return AbilitiesResource::collection($this->collection);
    }
}
