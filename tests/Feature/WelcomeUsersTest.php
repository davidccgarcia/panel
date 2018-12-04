<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /**
     * @test
     */
    public function it_welcomes_users_with_nickname()
    {
        $this->get('greet/david/mono')
            ->assertStatus(200)
            ->assertSee('Bienvenido David, tu apodo es mono');
    }

    /**
     * @test
     */
    public function it_welcomes_users_without_nickname()
    {
        $this->get('greet/david')
            ->assertStatus(200)
            ->assertSee('Bienvenido David');
    }
}
