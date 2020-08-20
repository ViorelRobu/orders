<?php

namespace Tests\Unit;

use App\Article;
use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use App\ProductType;
use App\Quality;
use App\Species;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolumeCalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Volume is calculated different based on the presence of length in the data
     *
     * @return void
     */
    public function testCalculateVolume()
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

        // if length is provided calculate for box
        $response = $this->actingAs($user)->json('POST', '/orders/1/details/add', [
            'article_id' => $article->id,
            'refinements_list' => [1, 2],
            'length' => '2000',
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'pal_pcs' => 1
        ]);

        $this->assertDatabaseCount('order_details', 1);

        $this->assertDatabaseHas('order_details', [
            'id' => 1,
            'article_id' => $article->id,
            'refinements_list' => '1,2',
            'length' => '2000',
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'volume' => '0.72'
        ]);

        // if length is null calculate volume for cilinder
        $round = $this->actingAs($user)->json('POST', '/orders/1/details/add', [
            'article_id' => $article->id,
            'refinements_list' => [1, 2],
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'pal_pcs' => 1
        ]);

        $this->assertDatabaseCount('order_details', 2);

        $this->assertDatabaseHas('order_details', [
            'id' => 2,
            'article_id' => $article->id,
            'refinements_list' => '1,2',
            'length' => null,
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'volume' => '0.057'
        ]);

    }
}
