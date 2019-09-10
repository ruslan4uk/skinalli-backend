<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * Проверяем если не указан пароль
     *
     * @return void
     */
    public function test_login_without_password()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/auth/login', ['email' => 'rusel91@idz.ru']);

        $response->assertStatus(422);
    }

    /**
     * Проверяем если не указан пароль
     *
     * @return void
     */
    public function test_login_without_email_login()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/auth/login', ['password' => '123456789']);

        $response->assertStatus(422);
    }

    public function test_login_success()
    {
        $user = factory(\App\User::class)->create();

//        dd($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}
