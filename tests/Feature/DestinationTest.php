<?php

namespace Tests\Feature;

use App\Country;
use App\Customer;
use App\Destination;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestinationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the destination API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeDestinations()
    {
        $this->denyAccess('/customers/1/destinations', 'get');
    }

    /**
     * If a destination doesn't exist it will be created and the id will be returned
     *
     * @return void
     */
    public function testCreateNewDestinationIfItDoesntExistsInDB()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $customer = factory(Customer::class)->create();
        $destination = factory(Destination::class)->create();

        $response = $this->actingAs($user)->json('POST', '/customers/1/destinations/find', [
            'customer_id' => $customer->id,
            'address' => 'adresa noua care nu exista',
            'country_id' => $country->id
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'result' => 'success',
                'data' => 2
                ]);

        $this->assertDatabaseCount('destinations', 2);
    }

    /**
     * Users that are logged in can see different destinations
     *
     * @return void
     */
    public function testLoggedInUsersCanSeeDestinationsForEachSupplier()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $customer = factory(Customer::class)->create();
        $destination = factory(Destination::class, 2)->create();
        $response = $this->actingAs($user)->json('GET', '/customers/1/destinations');

        $response->assertStatus(200)
            ->assertJson(['result' => 'success']);

        $this->assertDatabaseCount('destinations', 2);
    }

    /**
     * Users that are logged in can see different destinations
     *
     * @return void
     */
    public function testLoggedInUsersCanSearchThroughDestinations()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $customer = factory(Customer::class)->create();
        $destination = factory(Destination::class, 2)->create();
        $response = $this->actingAs($user)->json('POST', '/customers/1/destinations/search', [
            'customer_id'=> 1,
            'country_id' => 1
            ]);

        $response->assertStatus(200)
            ->assertJson(['result' => 'success']);
    }
}
