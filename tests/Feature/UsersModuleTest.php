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
    public function it_shows_the_user_list()
    {
        $this->get('users')
            ->assertStatus(200)
            ->assertSee('List of users')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /**
     * @test
     */
    public function it_shows_a_default_message_if_the_user_list_is_empty()
    {
        $this->get('users/?empty')
            ->assertStatus(200)
            ->assertSee('No hay usuarios registrados.');
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

    /**
     * @tests
     */
    public function it_loads_the_edit_users_page()
    {
        $this->get('users/5/edit')
            ->assertStatus(200)
            ->assertSee('Editar usuario: 5');
    }

    /**
     * @tests
     */
    public function it_not_loads_the_edit_users_page_with_text()
    {
        $this->get('users/text/edit')
            ->assertStatus(404)
            ->assertSee('Sorry, the page you are looking for could not be found.');
    }
}
