<?php

namespace Tests\Feature;

use App\Article;
use App\ProductType;
use App\Quality;
use App\Refinement;
use App\Species;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users that are not logged in cannot access the articles API endpoints
     *
     * @return void
     */
    public function testUnauthenticatedUsersCannotSeeArticles()
    {
        $this->denyAccess('/articles', 'get');
        $this->denyAccess('/articles/all', 'get');
        $this->denyAccess('/articles/fetch', 'get');
        $this->denyAccess('/articles/add', 'post');
        $this->denyAccess('/articles/1/update', 'patch');
    }

    /**
     * Logged in users can access the articles API endpoints
     *
     * @return void
     */
    public function testLoggedInUsersCanViewArticles()
    {
        $species = factory(Species::class, 3)->create();
        $quality = factory(Quality::class, 4)->create();
        $product = factory(ProductType::class, 2)->create();
        $refinements = factory(Refinement::class, 4)->create();
        $article = factory(Article::class)->create();

        $this->allowAccess('/articles');
        $this->allowAccess('/articles/all');
        $this->allowAccess('/articles/fetch?id=1');
    }

    /**
     * Logged in users can add a new article to the database
     *
     * @return void
     */
    public function testLoggedInUserCanAddNewArticle()
    {
        $user = factory(User::class)->create();
        $species = factory(Species::class)->create();
        $quality = factory(Quality::class)->create();
        $product = factory(ProductType::class)->create();
        $refinements = factory(Refinement::class, 5)->create();
        $default_refinements = [1, 2];
        $response = $this->actingAs($user)->json('POST', '/articles/add', [
            'name' => 'PLA-FI-DIY-18x200-C',
            'species_id' => $species->id,
            'quality_id' => $quality->id,
            'product_type_id' => $product->id,
            'default_refinements' => $default_refinements,
            'thickness' => 18,
            'width' => 200,
        ]);

        $response->assertStatus(201)
            ->assertJson(['created' => true]);

        $this->assertDatabaseHas('articles', [
            'name' => 'PLA-FI-DIY-18x200-C',
            'species_id' => $species->id,
            'quality_id' => $quality->id,
            'product_type_id' => $product->id,
            'default_refinements' => '1,2',
            'thickness' => 18,
            'width' => 200,
        ]);
    }

    /**
     * Logged in users can update an article in the database
     *
     * @return void
     */
    public function testLoggedInUserCanUpdateAnArticle()
    {
        $user = factory(User::class)->create();
        $species = factory(Species::class, 3)->create();
        $quality = factory(Quality::class, 4)->create();
        $product = factory(ProductType::class, 2)->create();
        $refinements = factory(Refinement::class, 4)->create();
        $article = factory(Article::class)->create();
        $default_refinements = [3,4];

        $response = $this->actingAs($user)->json('PATCH', '/articles/1/update', [
            'name' => 'PLA-FI-DIY-18x200-C',
            'species_id' => 2,
            'quality_id' => 4,
            'product_type_id' => 2,
            'default_refinements' => $default_refinements,
            'thickness' => 18,
            'width' => 200,
        ]);

        $response->assertStatus(200)
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('articles', [
            'name' => 'PLA-FI-DIY-18x200-C',
            'species_id' => 2,
            'quality_id' => 4,
            'product_type_id' => 2,
            'default_refinements' => '3,4',
            'thickness' => 18,
            'width' => 200,
        ]);
        $this->assertDatabaseMissing('countries', [
            'name' => $article->name,
            'species_id' => $article->species_id,
            'quality_id' => $article->quality_id,
            'product_type_id' => $article->product_type_id,
            'default_refinements' => $article->default_refinements,
            'thickness' => $article->thickness,
            'width' => $article->width,
        ]);
    }
}
