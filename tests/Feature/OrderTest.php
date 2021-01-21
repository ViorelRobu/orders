<?php

namespace Tests\Feature;

use App\Article;
use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use App\OrderDetail;
use App\OrderNumber;
use App\ProductType;
use App\Quality;
use App\Species;
use App\User;
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
        $orders = factory(Order::class)->create(['loading_date' => null]);
        $species = factory(Species::class, 4)->create();
        $quality = factory(Quality::class, 4)->create();
        $product_types = factory(ProductType::class, 4)->create();
        $article = factory(Article::class, 4)->create();
        $details = factory(OrderDetail::class)->create(['order_id' => 1]);

        // run asserts
        $this->allowAccess('/orders');
        $this->allowAccess('/orders/all');
        $this->allowAccess('/orders/fetch?id=1');
    }

    /**
     * Logged in users can access the archive orders API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewArchive()
    {
        // seed the data
        $country = factory(Country::class, 3)->create();
        $customers = factory(Customer::class, 4)->create();
        $destinations = factory(Destination::class, 4)->create();
        $orders = factory(Order::class)->create(['archived' => 1]);

        // run asserts
        $this->allowAccess('/archive');
        $this->allowAccess('/archive/all');
    }

    /**
     * Logged in users can add a new order
     *
     * @return void
     */
    public function testLoggedInUsersCanAddNewOrders()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $customer = factory(Customer::class)->create();
        $destination = factory(Destination::class)->create();
        $no = factory(OrderNumber::class)->create();

        $response = $this->actingAs($user)->post('/orders/add', [
            'customer_id' => $customer->id,
            'customer_order' => '01_hna/11',
            'auftrag' => '204-00123',
            'destination_id' => $destination->id,
            'customer_kw' => '2020-07-29',
            'production_kw' => '2020-07-23',
            'delivery_kw' => '2020-08-03',
            'eta' => '2020-08-03',
        ]);

        $response->assertRedirect('/orders/1/show');

        $this->assertDatabaseHas('orders', [
            'order' => $no->start_number,
            'customer_id' => $customer->id,
            'customer_order' => '01_hna/11',
            'auftrag' => '204-00123',
            'destination_id' => $destination->id,
            'customer_kw' => '2020-07-29',
            'production_kw' => '2020-07-23',
            'delivery_kw' => '2020-08-03',
            'eta' => '2020-08-03',
        ]);
    }

    /**
     * Logged in user can update an orders
     *
     * @return void
     */
    public function testLoggedInUsersCanUpdateOrder()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/orders/1/update', [
            'customer_id' => $customer[3]->id,
            'customer_order' => '01_hna/11',
            'auftrag' => '204-00123',
            'destination_id' => $destination[3]->id,
            'customer_kw' => '2020-07-29',
            'production_kw' => '2020-07-23',
            'delivery_kw' => '2020-08-03',
            'eta' => '2020-08-06',
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer[3]->id,
            'customer_order' => '01_hna/11',
            'auftrag' => '204-00123',
            'destination_id' => $destination[3]->id,
            'customer_kw' => '2020-07-29',
            'production_kw' => '2020-07-23',
            'delivery_kw' => '2020-08-03',
            'eta' => '2020-08-06',
        ]);

        $this->assertDatabaseMissing('orders', [
            'order' => $order->order,
            'customer_id' => $order->customer_id,
            'customer_order' => $order->customer_order,
            'auftrag' => $order->auftrag,
            'destination_id' => $order->destination_id,
            'customer_kw' => $order->customer_kw,
            'production_kw' => $order->production_kw,
            'delivery_kw' => $order->delivery_kw,
            'eta' => $order->eta,
        ]);

    }

    /**
     * Logged in user can update the priority
     *
     * @return void
     */
    public function testLoggedInUsersCanUpdatePriority()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/orders/1/update/priority', [
            'priority' => 'abc',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'priority' => 'abc',
        ]);

        $this->assertDatabaseMissing('orders', [
            'priority' => $order->priority,
        ]);

    }

    /**
     * Logged in user can update the details
     *
     * @return void
     */
    public function testLoggedInUsersCanUpdateDetails()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/orders/1/update/details', [
            'customer_id' => 4,
            'customer_order' => 'testing',
            'auftrag' => '204-test',
            'destination_id' => 4,
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'customer_id' => 4,
            'customer_order' => 'testing',
            'auftrag' => '204-test',
            'destination_id' => 4,
        ]);

        $this->assertDatabaseMissing('orders', [
            'customer_id' => $order->customer_id,
            'customer_order' => $order->customer_order,
            'auftrag' => $order->auftrag,
            'destination_id' => $order->destination_id
        ]);

    }

    /**
     * Logged in user can add and update observations
     *
     * @return void
     */
    public function testLoggedInUsersCanUpdateObservations()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/orders/1/update/observations', [
            'observations' => 'this is my custom observation',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'observations' => 'this is my custom observation',
        ]);

        $this->assertDatabaseMissing('orders', [
            'observations' => $order->observations,
        ]);

    }

    /**
     * Logged in user can load trucks and archive orders
     *
     * @return void
     */
    public function testLoggedInUsersCanLoadTrucks()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->from('/orders/1/show')->patch('/orders/1/ship', [
            'loading_date' => '9999-12-31',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/orders/1/show');

        $this->assertDatabaseHas('orders', [
            'loading_date' => '9999-12-31',
            'comment' => 'livrare completa',
        ]);

        $this->assertDatabaseMissing('orders', [
            'loading_date' => $order->loading_date,
        ]);

    }

    /**
     * Logged in user can ship incomplete orders
     *
     * @return void
     */
    public function testLoggedInUsersCanShipIncompleteOrders()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class, 3)->create();
        $articles = factory(Article::class, 3)->create();
        $order_details = factory(OrderDetail::class, 2)->create();

        // set one package to be delivered
        $delivered = OrderDetail::find(1);
        $delivered->produced_ticom = 1;
        $delivered->loading_date = '9999-12-31';
        $delivered->save();

        // set the 2nd package loading date to null
        $delivered = OrderDetail::find(2);
        $delivered->produced_ticom = 0;
        $delivered->loading_date = null;
        $delivered->save();

        $response = $this->actingAs($user)->from('/orders/1/show')->patch('/orders/1/ship/partial', [
            'loading_date' => '9999-12-31',
            'comment' => 'livrare partiala'
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/orders/1/show');

        $this->assertDatabaseHas('order_details', [
            'id' => 1,
            'loading_date' => '9999-12-31',
        ]);

        $this->assertDatabaseHas('order_details', [
            'id' => 2,
            'loading_date' => null,
        ]);

        $this->assertDatabaseHas('orders', [
            'loading_date' => '9999-12-31',
            'comment' => 'livrare partiala',
        ]);
    }

    /**
     * Logged in user can archive orders
     *
     * @return void
     */
    public function testLoggedInUsersCanArchiveOrders()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->from('/orders/1/show')->patch('/orders/1/archive', [
            'comment' => 'this is a test',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/orders/1/show');

        $this->assertDatabaseHas('orders', [
            'loading_date' => $order->loading_date,
            'archived' => 1,
            'comment' => 'this is a test',
        ]);
    }

    /**
     * Logged in user can change planning dates (customer kw, production kw, delivery kw and eta)
     *
     * @return void
     */
    public function testLoggedInUsersCanChangePlanningDates()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/orders/1/update/dates', [
            'customer_kw' => '9999-12-31',
            'production_kw' => '9999-12-31',
            'delivery_kw' => '9999-12-31',
            'eta' => '9999-12-31',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'customer_kw' => '9999-12-31',
            'production_kw' => '9999-12-31',
            'delivery_kw' => '9999-12-31',
            'eta' => '9999-12-31',
        ]);

        $this->assertDatabaseMissing('orders', [
            'customer_kw' => $order->customer_kw,
            'production_kw' => $order->production_kw,
            'delivery_kw' => $order->delivery_kw,
            'eta' => $order->eta,
        ]);

    }

    /**
     * Users can add details fields
     *
     * @return void
     */
    public function testUsersCanAddDetailsFields()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        $response = $this->actingAs($user)->json('POST', '/orders/1/fields', [
            'details_fields' => 'sticker|cod_ean',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'details_fields' => 'sticker|cod_ean',
        ]);
    }

    /**
     * Users cand add new fields to the order
     *
     * @return void
     */
    public function testUsersCanUpdateDetailsFieldsAndAddNewFields()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();
        $species = factory(Species::class, 4)->create();
        $quality = factory(Quality::class, 4)->create();
        $product_types = factory(ProductType::class, 4)->create();
        $articles = factory(Article::class, 4)->create();

        $response = $this->actingAs($user)->json('POST', '/orders/1/fields', [
            'details_fields' => 'sticker|cod_ean',
        ]);

        $details = factory(OrderDetail::class)->create(['order_id' => 1]);

        $new = $this->actingAs($user)->json('POST', '/orders/1/fields', [
            'details_fields' => 'camp_nou|test',
        ]);

        $this->assertDatabaseHas('orders', [
            'details_fields' => 'sticker|cod_ean|camp_nou|test',
        ]);

        $this->assertDatabaseHas('order_details', [
            'details_json' => '{"sticker":"test","cod_ean":"EAN","camp_nou":"","test":""}'
        ]);
    }
}
