<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_the_login_returns_a_unprocessable_entity_response(): void
    {
        $response = $this->postJson(route('login'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_the_login_returns_a_unauthorized_response(): void
    {
        $response = $this->postJson(
            uri: route('login'),
            data: [
                'email' => 'john.doe@email.com',
                'password' => 'password'
            ]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_the_login_returns_a_successfull_response(): void
    {
        $email = 'john.doe@email.com';
        $password = 'password';

        User::factory()->create(['password' => $password, 'email' => $email]);
        $response = $this->postJson(
            uri: route('login'),
            data: [
                'email' => $email,
                'password' => $password
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
    }
}
