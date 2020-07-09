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
        $this->denyAccess('/countries', 'get');
        $this->denyAccess('/countries/all', 'get');
        $this->denyAccess('/countries/fetch', 'get');
        $this->denyAccess('/countries/add', 'post');
        $this->denyAccess('/countries/1/update', 'patch');
    }

    /**
     * Logged in users can access the countries API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewCountries()
    {
        $this->allowAccess('/countries');
        $this->allowAccess('/countries/all');
        $this->allowAccess('/countries/fetch');
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
