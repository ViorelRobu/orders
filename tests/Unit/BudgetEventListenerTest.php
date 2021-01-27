<?php

namespace Tests\Unit;

use App\Article;
use App\Budget;
use App\Country;
use App\Customer;
use App\Destination;
use App\Events\FinishedUpdatingProductionAndDeliveries;
use App\Listeners\UpdateBudgetWithDeliveries;
use App\Order;
use App\OrderDetail;
use App\ProductType;
use App\Quality;
use App\Species;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class BudgetEventListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the listener will update the budget with the sum of values of the packages delivered
     *
     * @return void
     */
    public function testListenerWillUpdateBudgetWithDeliveries()
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
        $order_details = factory(OrderDetail::class)->create(['order_id' => $orders->id, 'loading_date' => '2021-02-01']);
        $budget = factory(Budget::class)->create(['year' => 2021]);

        // run the listener
        $listener = new UpdateBudgetWithDeliveries();
        $listener->handle(new FinishedUpdatingProductionAndDeliveries());

        // assert the data is in the database
        $this->assertDatabaseHas('budget', [
            'delivered' => $order_details->volume
        ]);
    }

    /**
     * Test event dispatching after full shipment
     *
     * @return void
     */
    public function testEventDispatchAfterFullShipment()
    {
        // set up required data factories
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        Event::fake([
            FinishedUpdatingProductionAndDeliveries::class
        ]);

        $payload = [
            'loading_date' => '9999-12-31'
        ];

        $response = $this->actingAs($user)->patch('/orders/1/ship', $payload);

        Event::assertDispatched(FinishedUpdatingProductionAndDeliveries::class);

    }

    /**
     * Test event dispatching after partial shipment
     *
     * @return void
     */
    public function testEventDispatchedAfterPartialShipment()
    {
        // set up required data factories
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        Event::fake([
            FinishedUpdatingProductionAndDeliveries::class
        ]);

        $payload = [
            'loading_date' => '9999-12-31',
            'comment' => 'testing'
        ];

        $response = $this->actingAs($user)->patch('/orders/1/ship/partial', $payload);

        Event::assertDispatched(FinishedUpdatingProductionAndDeliveries::class);

    }

    /**
     * Test event dispatched after Excel upload for deliveries
     *
     * @return void
     */
    public function testEventDispatchedAfterExcelUploadForDeliveries()
    {
        // set up required data factories
        $user = factory(User::class)->create();
        $country = factory(Country::class, 4)->create();
        $customer = factory(Customer::class, 4)->create();
        $destination = factory(Destination::class, 4)->create();
        $order = factory(Order::class)->create();

        Event::fake([
            FinishedUpdatingProductionAndDeliveries::class
        ]);

        Excel::fake();

        $this->actingAs($user)->post('/import/deliveries/start');

        Event::assertDispatched(FinishedUpdatingProductionAndDeliveries::class);
    }
}
