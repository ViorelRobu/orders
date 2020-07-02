<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Country;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the countries API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeCustomers()
    {
        $response = $this->get('/customers');
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
        $response = $this->actingAs($user)->get('/customers');
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get('/customers/all');
        $response->assertStatus(200);
    }

    public function testLoggedInUsersCanAddCustomer()
    {
        $user = factory(User::class)->create();
        $customer = 'Client nou!';
        $country = factory(Country::class)->create();
        $response = $this->actingAs($user)->post('/customers/add', [
            'name' => $customer,
            'country_id' => $country->id
        ]);
        $this->assertDatabaseHas('customers', [
            'name' => $customer,
            'country_id' => $country->id
        ]);
    }

    public function testLoggedInUsersCanUpdateCustomerDetails()
    {
        $user = factory(User::class)->create();
        $customer = 'Client nou!';
        $newCustomer = 'Alt client';
        $country = factory(Country::class, 3)->create();
        $this->actingAs($user)->post('/customers/add', [
            'name' => $customer,
            'country_id' => $country[0]->id
        ]);
        $this->patch('/customers/1/update', [
            'name' => $newCustomer,
            'country_id' => $country[1]->id
        ]);
        $this->assertDatabaseHas('customers', [
            'name' => $newCustomer,
            'country_id' => $country[1]->id
        ]);
        $this->assertDatabaseMissing('customers', [
            'name' => $customer,
            'country_id' => $country[0]->id
        ]);
    }
}
