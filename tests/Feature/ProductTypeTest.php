<?php

namespace Tests\Feature;

use App\ProductType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTypeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the product types API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeProducts()
    {
        $this->denyAccess('/products', 'get');
        $this->denyAccess('/products/all', 'get');
        $this->denyAccess('/products/fetch', 'get');
        $this->denyAccess('/products/add', 'post');
        $this->denyAccess('/products/1/update', 'patch');
    }

    /**
     * Logged in users can access the product types API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewProducts()
    {
        // seed the data
        $species = factory(ProductType::class)->create();

        // run asserts
        $this->allowAccess('/products');
        $this->allowAccess('/products/all');
        $this->allowAccess('/products/fetch?id=1');
    }

    /**
     * Logged in users can add a new product type to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewProduct()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->json('POST', '/products/add', [
            'name' => 'DIY'
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('product_types', [
            'name' => 'DIY'
        ]);
    }

    /**
     * Logged in users can update a product type in the database
     *
     * @return void
     */
    public function testLoggedInUserCanUpdateProduct()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductType::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/products/1/update', [
            'name' => 'DIY'
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('product_types', [
            'name' => 'DIY'
        ]);
        $this->assertDatabaseMissing('product_types', [
            'name' => $product->name
        ]);
    }

}
