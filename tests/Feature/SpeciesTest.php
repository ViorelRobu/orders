<?php

namespace Tests\Feature;

use App\Species;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpeciesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the species API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeSpecies()
    {
        $this->denyAccess('/species', 'get');
        $this->denyAccess('/species/all', 'get');
        $this->denyAccess('/species/fetch', 'get');
        $this->denyAccess('/species/add', 'post');
        $this->denyAccess('/species/1/update', 'patch');
    }

    /**
     * Logged in users can access the species API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewSpecies()
    {
        // seed the data
        $species = factory(Species::class)->create();

        // run asserts
        $this->allowAccess('/species');
        $this->allowAccess('/species/all');
        $this->allowAccess('/species/fetch?id=1');
    }

    /**
     * Logged in users can add a new species to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewSpecies()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->json('POST', '/species/add', [
            'name' => 'molid'
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('species', [
            'name' => 'molid'
        ]);
    }

    /**
     * Logged in users can update a species in the database
     *
     * @return void
     */
    public function testLoggedInUserCanUpdateSpecies()
    {
        $user = factory(User::class)->create();
        $species = factory(Species::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/species/1/update', [
            'name' => 'pin'
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('species', [
            'name' => 'pin'
        ]);
        $this->assertDatabaseMissing('species', [
            'name' => $species->name
        ]);
    }

}
