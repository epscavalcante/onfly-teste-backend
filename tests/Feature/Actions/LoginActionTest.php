<?php

namespace Tests\Feature;

use App\Actions\Login\LoginAction;
use App\Actions\Login\LoginActionOutput;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_the_login_throws_invalid_credentials_exception(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        LoginAction::handle('john.doe@email.com', '12345678');
    }

    public function test_the_login_handle_successfull(): void
    {
        $email = 'john.doe@email.com';
        $password = 'password';

        User::factory()->create(['password' => $password, 'email' => $email]);

        $output = LoginAction::handle(email: $email, password: $password);

        $this->assertInstanceOf(LoginActionOutput::class, $output);
    }
}
