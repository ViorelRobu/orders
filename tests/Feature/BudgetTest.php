<?php

namespace Tests\Feature;

use App\Budget;
use App\ProductType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the budget API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeBudget()
    {
        $this->denyAccess('/budget', 'get');
        $this->denyAccess('/budget/all', 'get');
        $this->denyAccess('/budget/fetch', 'get');
        $this->denyAccess('/budget/add', 'post');
        $this->denyAccess('/budget/1/update', 'patch');
    }

    /**
     * Logged in users can access the budget API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewBudget()
    {
        $this->allowAccess('/budget');
        $this->allowAccess('/budget/all');
        $this->allowAccess('/budget/fetch');
    }

    /**
     * Logged in users can add a new budget entry to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewBudget()
    {
        $user = factory(User::class)->create();
        $product_type = factory(ProductType::class)->create();

        $response = $this->actingAs($user)->json('POST', '/budget/add', [
            'group' => 1,
            'year' => 2021,
            'week' => 4,
            'volume' => 200
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('budget', [
            'product_type_id' => 1,
            'year' => 2021,
            'week' => 4,
            'volume' => 200
        ]);
    }

    /**
     * Logged in users can update budget entry in the database
     *
     * @return void
     */
    public function testLoggedInUserCanUpdateBudget()
    {
        $user = factory(User::class)->create();
        $product_type = factory(ProductType::class, 2)->create();
        $budget = factory(Budget::class)->create();

        $response = $this->actingAs($user)->json('PATCH', '/budget/1/update', [
            'group' => 2,
            'year' => $budget->year + 1,
            'week' => $budget->week + 1,
            'volume' => $budget->volume + 1
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('budget', [
            'product_type_id' => 2,
            'year' => $budget->year + 1,
            'week' => $budget->week + 1,
            'volume' => $budget->volume + 1
        ]);
        $this->assertDatabaseMissing('budget', [
            'product_type_id' => $budget->product_group_id,
            'year' => $budget->year,
            'week' => $budget->week,
            'volume' => $budget->volume
        ]);
    }
}
