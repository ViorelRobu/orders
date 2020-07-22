<?php

namespace Tests\Feature;

use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the orders API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeOrders()
    {
        $this->denyAccess('/orders', 'get');
        $this->denyAccess('/orders/all', 'get');
        $this->denyAccess('/orders/fetch', 'get');
        $this->denyAccess('/orders/add', 'post');
        $this->denyAccess('/orders/1/update', 'patch');
    }

    /**
     * Logged in users can access the orders API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewOrders()
    {
        // seed the data
        $country = factory(Country::class, 3)->create();
        $customers = factory(Customer::class, 4)->create();
        $destinations = factory(Destination::class, 4)->create();
        $orders = factory(Order::class)->create();

        // run asserts
        $this->allowAccess('/orders');
        $this->allowAccess('/orders/all');
        $this->allowAccess('/orders/fetch?id=1');
    }
}
