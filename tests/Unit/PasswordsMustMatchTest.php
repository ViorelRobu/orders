<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordsMustMatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPasswordsMustMatch()
    {
        // seed one test user
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/users/add', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'username' => 'test.test',
            'first_pass' => 'abcdefg',
            'second_pass' => 'abcdefgh'
        ])->assertStatus(500);

        $this->assertDatabaseCount('users', 1);
    }
}
