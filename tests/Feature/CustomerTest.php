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
        $this->denyAccess('/customers', 'get');
        $this->denyAccess('/customers/all', 'get');
        $this->denyAccess('/customers/fetch', 'get');
        $this->denyAccess('/customers/add', 'post');
        $this->denyAccess('/customers/1/update', 'patch');
    }

    /**
     * Logged in users can access the customers API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewCustomers()
    {
        $this->allowAccess('/customers', 'get');
        $this->allowAccess('/customers/all', 'get');
        $this->allowAccess('/customers/fetch', 'get');
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
