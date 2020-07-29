<?php

namespace Tests\Unit;

use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use App\OrderNumber;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderNumberingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Order numbers are automatic
     *
     * @return void
     */
    public function testOrderNumberingIsAutomatic()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $customer = factory(Customer::class, 3)->create();
        $destination = factory(Destination::class, 3)->create();
        $order = factory(Order::class)->create();
        $no = factory(OrderNumber::class)->create([
            'created_at' => (Carbon::now())->subMinutes(10)
            ]);

        $response = $this->actingAs($user)->post('/orders/add', [
            'customer_id' => 1,
            'customer_order' => '01_hna/11',
            'auftrag' => '204-00123',
            'destination_id' => 1,
            'customer_kw' => '2020-07-29',
            'production_kw' => '2020-07-23',
            'delivery_kw' => '2020-08-03',
            'month' => 7,
            'loading_date' => '2020-08-06',
            'priority' => 1,
            'observations' => 'Aceasta este o observatie',
        ]);

        $this->assertDatabaseCount('orders', 2);

        $this->assertDatabaseHas('orders', [
            'order' => $order->order + 1
        ]);
    }

    /**
     * Order numbers can be manually reset
     *
     * @return void
     */
    public function testOrderNumbersCanBeReset()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $customer = factory(Customer::class, 3)->create();
        $destination = factory(Destination::class, 3)->create();
        $order = factory(Order::class)->create();
        $no = factory(OrderNumber::class)->create();

        $response = $this->actingAs($user)->post('/orders/add', [
            'customer_id' => 1,
            'customer_order' => '01_hna/11',
            'auftrag' => '204-00123',
            'destination_id' => 1,
            'customer_kw' => '2020-07-29',
            'production_kw' => '2020-07-23',
            'delivery_kw' => '2020-08-03',
            'month' => 7,
            'loading_date' => '2020-08-06',
            'priority' => 1,
            'observations' => 'Aceasta este o observatie',
        ]);

        $this->assertDatabaseCount('orders', 2);

        $this->assertDatabaseHas('orders', [
            'order' => $no->start_number
        ]);

        $this->assertDatabaseHas('orders', [
            'order' => $order->order
        ]);
    }
}
