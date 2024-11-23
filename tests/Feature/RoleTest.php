<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Bouncer;

class RoleTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * obtener todos los roles del sistema.
     *
     * @test
     */
    public function testGetRolesAuth()
    {
        $this->signIn('roles-index', 'listar roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $response = $this->getJson($this->baseUrl.'roles');

        $response->assertStatus(200)->assertJson([
            'data'=>[
                [
                    'id'=>$role->id,
                    'nombre' => $role->name,
                    'titulo' => $role->title,
                ]
                ]
        ]);
    }

    /**
     * crear rol en el sistema
     *
     * @test
     */
    public function testStoreRolesAuthSuccess()
    {
        $this->signIn('roles-create', 'crear roles');

        $ability = Bouncer::ability()->create([
            'name' => 'prueba',
            'title' => 'Prueba',
        ]);
       $data=[
         'nombre'=>'prueba',
         'titulo'=>'prueba',
         'habilidades'=>[$ability->id]
       ];

        $response = $this->postJson($this->baseUrl.'roles',$data);

        $response->assertStatus(201);
    }

    /**
     * crear rol en el sistema error de validacion
     *
     * @test
     */
    public function testStoreRolesAuthErrorValidation()
    {
        $this->signIn('roles-create', 'crear roles');

        $ability = Bouncer::ability()->create([
            'name' => 'prueba',
            'title' => 'Prueba',
        ]);

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);

        $data=[
            'nombre'=>'prueba',
            'titulo'=>'prueba',
            'habilidades'=>[$ability->id]
        ];

        $response = $this->postJson($this->baseUrl.'roles',$data);

        $response->assertStatus(422);
    }

    /**
     * crear rol en el sistema error de validacion
     *
     * @test
     */
    public function testStoreRolesAuthErrorAbility()
    {
        $this->signIn('roles-index', 'crear roles');

        $ability = Bouncer::ability()->create([
            'name' => 'prueba',
            'title' => 'Prueba',
        ]);

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);

        $data=[
            'nombre'=>'prueba',
            'titulo'=>'prueba',
            'habilidades'=>[$ability->id]
        ];

        $response = $this->postJson($this->baseUrl.'roles',$data);

        $response->assertStatus(403);
    }

    /**
     * mostrar un solo rol
     *
     * @test
     */
    public function testShowRoleAuth()
    {
        $this->signIn('roles-show', 'mostrar roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $response = $this->getJson($this->baseUrl.'roles/'.$role->id);

        $response->assertStatus(200)->assertJson([
                'data'=>[
                    'id'=>$role->id,
                    'nombre' => $role->name,
                    'titulo' => $role->title,
                ]

        ]);
    }

    /**
     * mostrar un solo rol, error por habilidad
     *
     * @test
     */
    public function testShowRoleAuthErrorAbility()
    {
        $this->signIn('roles-index', 'listar roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $response = $this->getJson($this->baseUrl.'roles/'.$role->id);

        $response->assertStatus(403);
    }

    /**
     * editar rol en el sistema
     *
     * @test
     */
    public function testUpdateRolesAuthSuccess()
    {
        $this->signIn('roles-update', 'actualizar roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);

        $ability = Bouncer::ability()->create([
            'name' => 'prueba',
            'title' => 'Prueba',
        ]);
        $data=[
            'id'=>$role->id,
            'nombre'=>$role->name,
            'titulo'=>$role->title,
            'habilidades'=>[$ability->id]
        ];

        $response = $this->putJson($this->baseUrl.'roles/'.$role->id,$data);

        $response->assertStatus(200);
    }


    /**
     * editar rol en el sistema, error validacion
     *
     * @test
     */
    public function testUpdateRolesAuthErrorValidation()
    {
        $this->signIn('roles-update', 'actualizar roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $role2 = Bouncer::role()->create([
            'name' => 'prueba1',
            'title' => 'prueba1',
        ]);

        $ability = Bouncer::ability()->create([
            'name' => 'prueba',
            'title' => 'Prueba',
        ]);
        $data=[
            'id'=>$role->id,
            'nombre'=>$role2->name,
            'titulo'=>$role2->title,
            'habilidades'=>[$ability->id]
        ];

        $response = $this->putJson($this->baseUrl.'roles/'.$role->id,$data);

        $response->assertStatus(422);
    }

    /**
     * editar rol en el sistema, error habilidad
     *
     * @test
     */
    public function testUpdateRolesAuthErrorAbility()
    {
        $this->signIn('roles-create', 'crear roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);
        $role2 = Bouncer::role()->create([
            'name' => 'prueba1',
            'title' => 'prueba1',
        ]);

        $ability = Bouncer::ability()->create([
            'name' => 'prueba',
            'title' => 'Prueba',
        ]);
        $data=[
            'id'=>$role->id,
            'nombre'=>$role2->name,
            'titulo'=>$role2->title,
            'habilidades'=>[$ability->id]
        ];

        $response = $this->putJson($this->baseUrl.'roles/'.$role->id,$data);

        $response->assertStatus(403);
    }

    /**
     * eliminar rol en el sistema
     *
     * @test
     */
    public function testDeleteRolesAuthSuccess()
    {
        $this->signIn('roles-delete', 'eliminar roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);


        $response = $this->deleteJson($this->baseUrl.'roles/'.$role->id);

        $response->assertStatus(204);
    }

    /**
     * eliminar rol en el sistema,error habilidad
     *
     * @test
     */
    public function testDeleteRolesAuthErrorAbility()
    {
        $this->signIn('roles-update', 'eliminar roles');

        $role = Bouncer::role()->create([
            'name' => 'prueba',
            'title' => 'prueba',
        ]);


        $response = $this->deleteJson($this->baseUrl.'roles/'.$role->id);

        $response->assertStatus(403);
    }
}
