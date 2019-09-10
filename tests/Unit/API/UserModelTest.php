<?php

namespace Tests\Unit\API;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUserLogin()
    {
        $user = factory(User::class)->create();


        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}
