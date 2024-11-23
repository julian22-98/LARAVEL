<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Bouncer;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * habilidades de usuario
         */
        $indexRoles = Bouncer::ability()->create([
            'name' => 'Roles-index',
            'title' => 'Listar roles',
        ]);
        $showRoles = Bouncer::ability()->create([
            'name' => 'Roles-show',
            'title' => 'Detalle roles',
        ]);
        $createRoles = Bouncer::ability()->create([
            'name' => 'Roles-create',
            'title' => 'Crear roles',
        ]);
        $updateRoles = Bouncer::ability()->create([
            'name' => 'Roles-update',
            'title' => 'Editar roles',
        ]);
        $deleteRoles = Bouncer::ability()->create([
            'name' => 'Roles-delete',
            'title' => 'Elimiar roles',
        ]);
    }
}
