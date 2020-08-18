<?php

namespace Tests\Feature;

use App\Article;
use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use App\OrderDetail;
use App\ProductType;
use App\Quality;
use App\Species;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderDetailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the order details API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeOrderDetails()
    {
        $this->denyAccess('/orders/1/details', 'get');
    }

    /**
     * Logged in users can access the order details API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewOrderDetails()
    {
        // seed the data
        $country = factory(Country::class, 3)->create();
        $species = factory(Species::class, 4)->create();
        $quality = factory(Quality::class, 4)->create();
        $product_types = factory(ProductType::class, 4)->create();
        $customers = factory(Customer::class, 4)->create();
        $destinations = factory(Destination::class, 4)->create();
        $articles = factory(Article::class, 4)->create();
        $orders = factory(Order::class)->create(['archived' => 1]);
        $order_details = factory(OrderDetail::class)->create(['order_id' => $orders->id]);

        // run asserts
        $this->allowAccess('/orders/1/details');
    }

    /**
     * Logged in users can add a new details
     *
     * @return void
     */
    public function testLoggedInUsersCanAddNewDetails()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 3)->create();
        $species = factory(Species::class, 4)->create();
        $quality = factory(Quality::class, 4)->create();
        $product_types = factory(ProductType::class, 4)->create();
        $customers = factory(Customer::class, 4)->create();
        $destinations = factory(Destination::class, 4)->create();
        $article = factory(Article::class)->create([
            'thickness' => 18,
            'width' => 200
        ]);
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->json('POST', '/orders/1/details/add', [
            'article_id' => $article->id,
            'refinements_list' => [1, 2],
            'length' => '2000',
            'pcs' => '100',
            'pal' => 2
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseCount('order_details', 2);

        $this->assertDatabaseHas('order_details', [
            'order_id' => 1,
            'article_id' => $article->id,
            'refinements_list' => '1,2',
            'length' => '2000',
            'pcs' => '100'
        ]);
    }

}