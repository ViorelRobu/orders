<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the species API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeUsersPage()
    {
        $this->denyAccess('/users', 'get');
    }

    /**
     * Logged in users can access the species API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewUsers()
    {
        // seed the data
        $users = factory(User::class)->create();

        // run asserts
        $this->allowAccess('/users');
    }

    public function testCanAddUsers()
    {
        // seed one test user
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/users/add', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'username' => 'test.test',
            'first_pass' => 'abcdefg',
            'second_pass' => 'abcdefg'
        ])->assertStatus(302);

        $this->assertDatabaseCount('users',2);
        $this->assertDatabaseHas('users', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'username' => 'test.test'
        ]);

    }
}
