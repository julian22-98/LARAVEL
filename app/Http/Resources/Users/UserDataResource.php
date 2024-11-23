<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'nombre_completo' => $this->fullName,
            'identificacion' => $this->identification,
            'correo' => $this->email,
            'estado' => $this->active,
            'rol' => $this->roles[0]['name'],
            'reset_password' =>$this->reset_password
        ];
     }
}
