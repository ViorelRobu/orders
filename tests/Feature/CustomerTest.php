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
        $this->allowAccess('/customers');
        $this->allowAccess('/customers/all');
        $this->allowAccess('/customers/fetch');
    }

    /**
     * Logged in users can add a new customer
     *
     * @return void
     */
    public function testLoggedInUsersCanAddCustomer()
    {
        $user = factory(User::class)->create();
        $customer = 'Client nou!';
        $fibu = 123456;
        $country = factory(Country::class)->create();
        $response = $this->actingAs($user)->json('POST', '/customers/add', [
            'fibu' => $fibu,
            'name' => $customer,
            'country_id' => $country->id
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('customers', [
            'fibu' => $fibu,
            'name' => $customer,
            'country_id' => $country->id
        ]);
    }

    /**
     * Logged in users can update the customer details
     *
     * @return void
     */
    public function testLoggedInUsersCanUpdateCustomerDetails()
    {
        $user = factory(User::class)->create();
        $fibu = 123456;
        $newFibu = 456789;
        $customer = 'Client nou!';
        $newCustomer = 'Alt client';
        $country = factory(Country::class, 3)->create();
        $this->actingAs($user)->post('/customers/add', [
            'fibu' => $fibu,
            'name' => $customer,
            'country_id' => $country[0]->id
        ]);
        $response = $this->json('PATCH', '/customers/1/update', [
            'fibu' => $newFibu,
            'name' => $newCustomer,
            'country_id' => $country[1]->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('customers', [
            'fibu' => $newFibu,
            'name' => $newCustomer,
            'country_id' => $country[1]->id
        ]);
        $this->assertDatabaseMissing('customers', [
            'fibu' => $fibu,
            'name' => $customer,
            'country_id' => $country[0]->id
        ]);
    }
}
