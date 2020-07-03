<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Test if unauthenticated users will be redirected to login page
     *
     * @param $uri
     * @param string $type
     * @param array $data
     */
    public function denyAccess($uri, $type = 'get', $data = [])
    {
            $response = $this->$type($uri);
            $response->assertRedirect('/login', $data);
    }

    /**
     * Test if logged in user is allowed to access certain endpoints
     *
     * @param $uri
     * @param string $type
     * @param array $data
     */
    public function allowAccess($uri, $type = 'get', $data = [])
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->$type($uri, $data);
        $response->assertStatus(200);
    }
}
