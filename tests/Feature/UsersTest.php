<?php

namespace Tests\Feature;

use App\Role;
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

    /**
     * Can add a new user
     *
     * @return void
     */
    public function testCanAddUsers()
    {
        // seed one test user
        $user = factory(User::class)->create();

        // seed a role
        $role = new Role();
        $role->role = 'administrator';
        $role->save();

        $response = $this->actingAs($user)->post('/users/add', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'username' => 'test.test',
            'role' => $role->id,
            'first_pass' => 'abcdefg',
            'second_pass' => 'abcdefg'
        ])->assertStatus(302);

        $this->assertDatabaseCount('users',2);
        $this->assertDatabaseHas('users', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'username' => 'test.test',
            'role_id' => $role->id,
            'is_active' => 1
        ]);

    }

    /**
     * A user status can be changed
     *
     * @return void
     */
    public function testUserStatusCanBeChanged()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/users/1/deactivate')->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'is_active' => 0
        ]);

        $response = $this->actingAs($user)->post('/users/1/activate')->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'is_active' => 1
        ]);
    }
}
