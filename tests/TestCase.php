<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use Bouncer;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp():void {
        parent::setUp();
//        $this->signIn();
    }
    public $baseUrl ="/api/";

    public function signIn($ability, $name){

        $user = Sanctum::actingAs(
            User::factory()->create(),
        );

        $admin= Bouncer::role()->create([
            'name' => 'admin',
            'title' => 'admin',
        ]);
        $ability = Bouncer::ability()->create([
            'name' => $ability,
            'title' => $name
        ]);

        Bouncer::allow($admin)->to($ability);

        Bouncer::assign($admin)->to($user);
    }
}
