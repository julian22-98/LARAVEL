<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class authTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     *  @test
     */
    public function loginUserBasicTest()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->getJson('/api/user');

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     *  @test
     */
    public function logoutUserBasicTest()
    {
       $user= Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->postJson('/api/logout');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     *  @test
     */
    public function getUserLoginBasicTest()
    {
        $user= Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->getJson('/api/user');
        $response->assertStatus(200)->assertJson([
           'name'=>$user->name,
        ]);
    }

    /**
     * A basic feature test example.
     *
     *  @test
     */
    public function loginUserInactiveBasicTest()
    {
        $auth=User::factory()->create(['active'=>false]);
        $data=[
            'email'=>$auth->email,
            'password'=>'password',
            'device_name'=>'test'
        ];
        $response = $this->postJson('/api/login',$data);
        $response->assertStatus(422);
    }
}
