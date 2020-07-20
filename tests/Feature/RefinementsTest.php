<?php

namespace Tests\Feature;

use App\Refinement;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RefinementsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the refinements API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeRefinements()
    {
        $this->denyAccess('/refinements', 'get');
        $this->denyAccess('/refinements/all', 'get');
        $this->denyAccess('/refinements/fetch', 'get');
        $this->denyAccess('/refinements/add', 'post');
        $this->denyAccess('/refinements/1/update', 'patch');
    }

    /**
     * Logged in users can access the refinements API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewRefinements()
    {
        // seed the data
        $refinements = factory(Refinement::class)->create();

        // run asserts
        $this->allowAccess('/refinements');
        $this->allowAccess('/refinements/all');
        $this->allowAccess('/refinements/fetch?id=1');
    }

    /**
     * Logged in users can add a new refinements to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewRefinements()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->json('POST', '/refinements/add', [
            'name' => 'BLUE',
            'description' => 'panouri cu albastreala'
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('refinements', [
            'name' => 'BLUE',
            'description' => 'panouri cu albastreala'
        ]);
    }

    /**
     * Logged in users can update a refinements in the database
     *
     * @return void
     */
    public function testLoggedInUserCanUpdateRefinements()
    {
        $user = factory(User::class)->create();
        $refinements = factory(Refinement::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/refinements/1/update', [
            'name' => 'BLUE',
            'description' => 'panouri cu albastreala'
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('refinements', [
            'name' => 'BLUE',
            'description' => 'panouri cu albastreala'
        ]);
        $this->assertDatabaseMissing('refinements', [
            'name' => $refinements->name,
            'description' => $refinements->description
        ]);
    }
}
