<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Bouncer;

class UsersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * listar todos los usuarios con paginacion
     *
     * @test
     */
    public function testGetUsersPagination()
    {
        $this->signIn('users-index', 'listar usuarios');

        User::factory(5)->create();

        $response = $this->getJson($this->baseUrl . 'users');

        $response->assertStatus(200)->assertJson([
            'data' => [],
        ]);
    }

    /**
     * crear un usuario nuevo
     *
     * @test
     */
    public function testStoreUsers()
    {
        $this->signIn('users-create', 'crear usuarios');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $data = [
            'nombre' => $this->faker->name,
            'apellido' => 'prueba',
            'identificacion' => '123456789',
            'correo' => 'example@example.com',
            'estado' => true,
            'cambio_password' => false,
            'password' => '123456',
            'password_confirmation' => '123456',
            'rol'=>$role->id
        ];
        $response = $this->postJson($this->baseUrl . 'users', $data);

        $response->assertStatus(201);
    }

    /**
     * crear un usuario nuevo, error de validacion
     *
     * @test
     */
    public function testStoreUsersErrorValidation()
    {
        $this->signIn('users-create', 'crear usuarios');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $user = User::factory()->create();
        $data = [
            'nombre' => $this->faker->name,
            'apellido' => 'prueba',
            'identificacion' => '123456789',
            'correo' => $user->email,
            'estado' => true,
            'cambio_password' => false,
            'password' => '123456',
            'password_confirmation' => '123456',
            'rol'=>$role->id
        ];
        $response = $this->postJson($this->baseUrl . 'users', $data);

        $response->assertStatus(422);
    }

    /**
     * crear un usuario nuevo, error de habilidad
     *
     * @test
     */
    public function testStoreUsersErrorAbility()
    {
        $this->signIn('users-index', 'listar usuarios');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $data = [
            'nombre' => $this->faker->name,
            'apellido' => 'prueba',
            'identificacion' => '123456789',
            'correo' => 'example@example.com',
            'estado' => true,
            'cambio_password' => false,
            'password' => '123456',
            'password_confirmation' => '123456',
            'rol'=>$role->id
        ];
        $response = $this->postJson($this->baseUrl . 'users', $data);

        $response->assertStatus(403);
    }

    /**
     * visualizar un usuario
     *
     * @test
     */
    public function testShowUsers()
    {
        $this->signIn('users-show', 'ver usuarios');

        $user = User::factory()->create();
        $response = $this->getJson($this->baseUrl . 'users/' . $user->id);

        $response->assertStatus(200);
    }

    /**
     * visualizar un usuario,error de habilidad
     *
     * @test
     */
    public function testShowUsersErrorAbility()
    {
        $this->signIn('users-index', 'listar usuarios');

        $user = User::factory()->create();
        $response = $this->getJson($this->baseUrl . 'users/' . $user->id);

        $response->assertStatus(403);
    }

    /**
     * actualizar usuarios
     *
     * @test
     */
    public function testUpdateUsers()
    {
        $this->signIn('users-update', 'actualizar usuarios');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);

        $user = User::factory()->create();

        $data = [
            'id'=>$user->id,
            'nombre' =>$user->name,
            'apellido' => $user->lastname,
            'identificacion' => $user->identification,
            'correo' => $user->email,
            'estado' => true,
            'cambio_password' => false,
            'rol'=>$role->id
        ];

        $response = $this->putJson($this->baseUrl . 'users/' . $user->id,$data);

        $response->assertStatus(200);
    }

    /**
     * actualizar usuarios,error validacion  error de email
     *
     * @test
     */
    public function testUpdateUsersErrorValidationEmail()
    {
        $this->signIn('users-update', 'actualizar usuarios');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $data = [
            'id'=>$user->id,
            'nombre' =>$user->name,
            'apellido' => $user->lastname,
            'identificacion' => $user->identification,
            'correo' => $user2->email,
            'estado' => true,
            'cambio_password' => false,
            'password' => '123456',
            'password_confirmation' => '123456',
            'rol'=>$role->id
        ];

        $response = $this->putJson($this->baseUrl . 'users/' . $user->id,$data);

        $response->assertStatus(422);
    }

    /**
     * actualizar usuarios,error validacion de identificacion
     *
     * @test
     */
    public function testUpdateUsersErrorValidationIdentification()
    {
        $this->signIn('users-update', 'actualizar usuarios');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $data = [
            'id'=>$user->id,
            'nombre' =>$user->name,
            'apellido' => $user->lastname,
            'identificacion' => $user2->identification,
            'correo' => $user->email,
            'estado' => true,
            'cambio_password' => false,
            'password' => '123456',
            'password_confirmation' => '123456',
            'rol'=>$role->id
        ];

        $response = $this->putJson($this->baseUrl . 'users/' . $user->id,$data);

        $response->assertStatus(422);
    }

    /**
     * actualizar usuarios,error habilidad
     *
     * @test
     */
    public function testUpdateUsersErrorAbility()
    {
        $this->signIn('users-create', 'crear usuarios');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);

        $user = User::factory()->create();

        $data = [
            'id'=>$user->id,
            'nombre' =>$user->name,
            'apellido' => $user->lastname,
            'identificacion' => $user->identification,
            'correo' => $user->email,
            'estado' => true,
            'cambio_password' => false,
            'password' => '123456',
            'password_confirmation' => '123456',
            'rol'=>$role->id
        ];

        $response = $this->putJson($this->baseUrl . 'users/' . $user->id,$data);

        $response->assertStatus(403);
    }


    /**
     * eliminar usuarios
     *
     * @test
     */
    public function testDeleteUsers()
    {
        $this->signIn('users-delete', 'eliminar usuarios');

        $user = User::factory()->create();


        $response = $this->deleteJson($this->baseUrl . 'users/' . $user->id);

        $response->assertStatus(204);
    }


    /**
     * eliminar usuarios
     *
     * @test
     */
    public function testDeleteUsersErrorAbility()
    {
        $this->signIn('users-update', 'actualizar usuarios');

        $user = User::factory()->create();

        $response = $this->deleteJson($this->baseUrl . 'users/' . $user->id);

        $response->assertStatus(403);
    }


}
