<?php

namespace Tests\Feature;

use App\Quality;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QualityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the quality API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeQuality()
    {
        $this->denyAccess('/quality', 'get');
        $this->denyAccess('/quality/all', 'get');
        $this->denyAccess('/quality/fetch', 'get');
        $this->denyAccess('/quality/add', 'post');
        $this->denyAccess('/quality/1/update', 'patch');
    }

    /**
     * Logged in users can access the quality API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewQuality()
    {
        // seed the data
        $species = factory(Quality::class)->create();

        // run asserts
        $this->allowAccess('/quality');
        $this->allowAccess('/quality/all');
        $this->allowAccess('/quality/fetch?id=1');
    }

    /**
     * Logged in users can add a new quality to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewQuality()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->json('POST', '/quality/add', [
            'name' => 'A'
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('quality', [
            'name' => 'A'
        ]);
    }

    /**
     * Logged in users can update a quality in the database
     *
     * @return void
     */
    public function testLoggedInUserCanUpdateQuality()
    {
        $user = factory(User::class)->create();
        $quality = factory(Quality::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/quality/1/update', [
            'name' => 'A'
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('quality', [
            'name' => 'A'
        ]);
        $this->assertDatabaseMissing('quality', [
            'name' => $quality->name
        ]);
    }
}
