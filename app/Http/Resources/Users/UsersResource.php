<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Roles\RolesCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'nombre_completo' => $this->fullName,
            'identificacion'=>$this->identification,
            'correo'=>$this->email,
            'estado'=>$this->active,
            'reinicio_password'=>$this->reset_password,
            'relaciones'=>[
                'roles'=>new RolesCollection($this->Roles)
            ]
        ];
    }
}
