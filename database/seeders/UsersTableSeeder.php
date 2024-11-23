<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Bouncer;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'name' => 'jose alejandro',
            'lastname'=> 'herrera villegas',
            'email' => 'admin@jose-herrera.com',
            'identification'=>'1234567890',
            'password' => bcrypt('123456'),
            'active'=>true,
            'email_verified_at' => date('Y-m-d H:m:s'),
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ]);

        //crear el rol administrador
        Bouncer::allow('admin')->everything();

        //asignar el rol de administrador
        Bouncer::assign('admin')->to($admin);

        //crear rol sin permisos
        $role2 = Bouncer::role()->create([
            'name' => 'ninguno',
            'title' => 'Ningun Rol',
        ]);

        /*
         * habilidades de usuario
         */
        $indexUsers = Bouncer::ability()->create([
            'name' => 'users-index',
            'title' => 'Ver usuarios',
        ]);
        $showUsers = Bouncer::ability()->create([
            'name' => 'users-show',
            'title' => 'Detalle usuario',
        ]);
        $createUsers = Bouncer::ability()->create([
            'name' => 'users-create',
            'title' => 'Crear usuarios',
        ]);
        $updateUsers = Bouncer::ability()->create([
            'name' => 'users-update',
            'title' => 'Editar usuarios',
        ]);
        $deleteUsers = Bouncer::ability()->create([
            'name' => 'users-delete',
            'title' => 'Elimiar usuarios',
        ]);
        $changeStatusUsers = Bouncer::ability()->create([
            'name' => 'users-status',
            'title' => 'Cambiar estado del usuario',
        ]);
        $revomeTokensUsers = Bouncer::ability()->create([
            'name' => 'users-remove-token',
            'title' => 'Cerrar sesión de los usuarios',
        ]);

    }
}
