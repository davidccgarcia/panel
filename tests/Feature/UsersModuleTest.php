<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_the_users_list_page()
    {
        $this->get('users')
            ->assertStatus(200)
            ->assertSee('Usuarios');
    }

    /**
     * @test
     */
    public function it_loads_the_users_details_page()
    {
        $this->get('users/50')
            ->assertStatus(200)
            ->assertSee('Mostrando el detalle del usuario: 50');
    }

    /**
     * @test
     */
    public function it_loads_the_new_users_page()
    {
        $this->get('users/new')
            ->assertStatus(200)
            ->assertSee('Crear nuevo usuario');
    }
}
