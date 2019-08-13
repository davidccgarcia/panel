<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_displays_the_users_details()
    {
        $user = factory(User::class)->create([
            'name' => 'David Garcia'
        ]);

        $this->get('users/' . $user->id)
            ->assertStatus(200)
            ->assertSee($user->name);
    }

    /**
     * @test
     */
    public function it_display_a_404_error_if_the_user_is_not_found()
    {
        $this->withExceptionHandling();

        $this->get('/users/1000')
            ->assertStatus(404)
            ->assertSee('Pagina no encontrada');
    }
}
