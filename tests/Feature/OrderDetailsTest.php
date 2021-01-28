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
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'pal_pcs' => 2
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseCount('order_details', 2);

        $this->assertDatabaseHas('order_details', [
            'order_id' => 1,
            'article_id' => $article->id,
            'refinements_list' => '1,2',
            'length' => '2000',
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
        ]);
    }

    /**
     * Users can update the each position in bulk (all packages at once)
     *
     * @return void
     */
    public function testUsersCanUpdateTheDetailsForAllThePositions()
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
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'pal_pcs' => 2,
            'details_json' => '{"ean":"44444"}'
        ]);

        $edit = $this->actingAs($user)->json('PATCH', '/orders/1/details/1/update', [
            'edit_article_id' => $article->id,
            'edit_refinements_list' => [4,5],
            'edit_length' => null,
            'edit_pcs' => 30,
            'edit_pcs_height' => '200',
            'edit_rows' => '200',
            'edit_label' => '200',
            'edit_foil' => 1,
            'edit_pal' => 'tac',
            'edit_details_json' => '{"ean":"44445"}'
        ]);

        $edit->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseCount('order_details', 2);

        $this->assertDatabaseHas('order_details', [
            'article_id' => $article->id,
            'refinements_list' => '4,5',
            'length' => null,
            'pcs' => '30',
            'pcs_height' => '200',
            'rows' => '200',
            'label' => '200',
            'foil' => 1,
            'pal' => 'tac',
            'details_json' => '{"ean":"44445"}'
        ]);

        $this->assertDatabaseMissing('order_details', [
            'article_id' => $article->id,
            'refinements_list' => '1,2',
            'length' => '2000',
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'details_json' => '{"ean":"44444"}'
        ]);
    }

    /**
     * Users can update the each position in bulk (all packages at once)
     *
     * @return void
     */
    public function testUsersCanUpdateTheDetailsForOnePosition()
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
        $details = factory(OrderDetail::class, 5)->create([
            'order_id' => $order->id,
            'article_id' => $article->id,
            'refinements_list' => '1,2',
            'thickness' => 18,
            'width' => 200,
            'length' => '2000',
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 'infoliat',
            'pal' => 'palet',
            'position' => 1,
            'details_json' => '{"ean":"44444"}'
        ]);

        $edit = $this->actingAs($user)->json('PATCH', '/orders/1/details/1/update/one', [
            'edit_article_id' => $article->id,
            'edit_refinements_list' => [4,5],
            'edit_length' => null,
            'edit_pcs' => 30,
            'edit_pcs_height' => '200',
            'edit_rows' => '200',
            'edit_label' => '200',
            'edit_foil' => 1,
            'edit_pal' => 'tac',
            'edit_details_json' => '{"ean":"44445"}'
        ]);

        $edit->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseCount('order_details', 5);

        $this->assertDatabaseHas('order_details', [
            'article_id' => $article->id,
            'refinements_list' => '4,5',
            'length' => null,
            'pcs' => 30,
            'pcs_height' => '200',
            'rows' => '200',
            'label' => '200',
            'foil' => 1,
            'position' => 2,
            'pal' => 'tac',
            'details_json' => '{"ean":"44445"}'
        ]);

        $this->assertDatabaseHas('order_details', [
            'thickness' => 18,
            'width' => 200,
            'length' => '2000',
            'pcs' => '100',
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 'infoliat',
            'pal' => 'palet',
            'position' => 1,
            'details_json' => '{"ean":"44444"}'
        ]);

        // if there is a single package on the position do not change the position number
        $edit2 = $this->actingAs($user)->json('PATCH', '/orders/1/details/1/update/one', [
            'edit_article_id' => $article->id,
            'edit_refinements_list' => [4, 5],
            'edit_length' => null,
            'edit_pcs' => 30,
            'edit_pcs_height' => '400',
            'edit_rows' => '200',
            'edit_label' => '200',
            'edit_foil' => 1,
            'edit_pal' => 'tac',
            'edit_details_json' => '{"ean":"44445"}'
        ]);

        $edit2->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseCount('order_details', 5);

        $this->assertDatabaseHas('order_details', [
            'article_id' => $article->id,
            'refinements_list' => '4,5',
            'length' => null,
            'pcs' => 30,
            'pcs_height' => '400',
            'rows' => '200',
            'label' => '200',
            'foil' => 1,
            'position' => 2,
            'pal' => 'tac',
            'details_json' => '{"ean":"44445"}'
        ]);
    }

    /**
     * A user can delete all the packages for one position
     *
     * @return void
     */
    public function testUsersCanDeleteAllPackagesOnOnePosition()
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
            'pal_pcs' => 3,
            'details_json' => '{"ean":"44444"}'
        ]);

        $delete = $this->actingAs($user)->json('DELETE', '/orders/1/details/1/delete');

        $delete->assertStatus(200)
            ->assertJson(['deleted' => true]);

        $this->assertDatabaseCount('order_details', 0);
    }

    /**
     * A user can delete one package
     *
     * @return void
     */
    public function testUsersCanDeleteOnePackage()
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
            'pal_pcs' => 3,
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'details_json' => '{"ean":"44444"}'
        ]);

        $delete = $this->actingAs($user)->json('DELETE', '/orders/1/details/package/delete', [
            'id' => 1
        ]);

        $delete->assertStatus(200)
            ->assertJson(['deleted' => true]);

        $this->assertDatabaseCount('order_details', 2);
    }

    /**
     * A user can copy a position
     *
     * @return void
     */
    public function testUsersCanCopyPositions()
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
            'pal_pcs' => 3,
            'pcs_height' => '100',
            'rows' => '100',
            'label' => '100',
            'foil' => 0,
            'pal' => 'palet',
            'details_json' => '{"ean":"44444"}'
        ]);

        $copy= $this->actingAs($user)->json('POST', '/orders/1/details/copy', [
            'id' => 1,
            'copies' => 5
        ]);

        $copy->assertStatus(200)
            ->assertJson(['copied' => true]);

        $this->assertDatabaseCount('order_details', 8);
    }

}
