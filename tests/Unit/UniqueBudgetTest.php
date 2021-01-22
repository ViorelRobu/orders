<?php

namespace Tests\Unit;

use App\Budget;
use App\ProductType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniqueBudgetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The combination of product group, year and week must be unique.
     *
     * @return void
     */
    public function testNewBudgetCombinationsMustBeUnique()
    {
        $user = factory(User::class)->create();
        $product_type = factory(ProductType::class)->create();
        $budget = factory(Budget::class)->create();

        // check for new
        $response = $this->actingAs($user)->json('post', '/budget/check/unique', [
            'id' => '',
            'group' => 1,
            'year' => $budget->year,
            'week' => $budget->week,
        ]);

        $response->assertStatus(200)
            ->assertJson(['exists' => true]);

        // check for update
        $response = $this->actingAs($user)->json('post', '/budget/check/unique', [
            'id' => 1,
            'group' => 1,
            'year' => $budget->year,
            'week' => $budget->week,
        ]);

        $response->assertStatus(200)
            ->assertJson(['exists' => false]);
    }
}
