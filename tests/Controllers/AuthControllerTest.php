<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testInvalidCredentials()
    {
        $this->json('POST', '/api/auth/token');

        $this->assertResponseStatus(401);
        $this->seeJsonStructure([
            'errors' => [[]],
        ]);
    }

    public function testCanGetAuthenticatedToken()
    {
        $user = factory(User::class)->create();

        $this->json('POST', '/api/auth/token', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => ['token'],
        ]);
    }
}
