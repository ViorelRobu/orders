<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the countries API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeCountries()
    {
        $response = $this->get('/countries');
        $response->assertRedirect('/login');
    }

    /**
     * Logged in users can access the countries API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewCountries()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/countries');
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get('/countries/all');
        $response->assertStatus(200);
    }

    /**
     * Logged in users can add a new country to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewCountry()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->post('/countries/add', [
            'name' => 'Romania'
        ]);
        $this->assertDatabaseHas('countries', [
            'name' => 'Romania'
        ]);
    }

    /**
     * Logged in users can update a country in the database
     *
     * @return void
     */
    public function testLoggedInUserCanUpdateACountry()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->post('/countries/add', [
            'name' => 'Romania'
        ]);
        $this->actingAs($user)->patch('/countries/1/update', [
            'name' => 'Bulgaria'
        ]);
        $this->assertDatabaseHas('countries', [
            'name' => 'Bulgaria'
        ]);
        $this->assertDatabaseMissing('countries', [
            'name' => 'Romania'
        ]);
    }
}
