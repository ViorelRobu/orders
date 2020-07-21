<?php

namespace Tests\Feature;

use App\OrderNumber;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderNumberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the order numbers API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeOrderNumbers()
    {
        $this->denyAccess('/numbers', 'get');
        $this->denyAccess('/numbers/all', 'get');
        $this->denyAccess('/numbers/add', 'post');
    }

    /**
     * Logged in users can access the order numbers API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewOrderNumbers()
    {
        // seed the data
        $numbers = factory(OrderNumber::class)->create();

        // run asserts
        $this->allowAccess('/numbers');
        $this->allowAccess('/numbers/all');
    }

    /**
     * Logged in users can add a new order number to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewOrderNumber()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->json('POST', '/numbers/add', [
            'start_number' => 2007001
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('order_numbers', [
            'start_number' => 2007001
        ]);
    }
}
