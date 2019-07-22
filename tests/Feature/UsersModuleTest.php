<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_shows_the_user_list()
    {
        factory(User::class)->create([
            'name' => 'Joel'
        ]);

        factory(User::class)->create([
            'name' => 'Ellie'
        ]);

        $this->get('users')
            ->assertStatus(200)
            ->assertSee('Listado de usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /**
     * @test
     */
    public function it_shows_a_default_message_if_the_user_list_is_empty()
    {
        $this->get('users')
            ->assertStatus(200)
            ->assertSee('No hay usuarios registrados.');
    }

    /**
     * @test
     */
    public function it_display_a_404_error_if_the_user_is_not_found()
    {
        $this->get('/users/1000')
            ->assertStatus(404)
            ->assertSee('Pagina no encontrada');
    }

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
    public function it_loads_the_new_users_page()
    {
        $this->withoutExceptionHandling();

        $this->get('users/create')
            ->assertStatus(200)
            ->assertSee('Crear usuario');
    }

    /**
     * @test
     */
    public function it_create_a_new_user()
    {
        $this->withoutExceptionHandling();

        $this->post('users', [
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456',
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/davidccgarcia'
        ])->assertRedirect(route('users.store'));

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/davidccgarcia',
            'user_id' => User::findByEmail('ccristhiangarcia@gmail.com')->id,
        ]);
    }

    /**
     * @test
     */
    public function the_name_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', [
            'name' => '',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ])->assertRedirect('users')
            ->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('users', [
            'email' => 'ccristhiangarcia@gmail.com',
        ]);
    }

    /**
     * @test
     */
    public function the_email_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', [
            'name' => 'David',
            'email' => '',
            'password' => '123456'
        ])->assertRedirect('users')
            ->assertSessionHasErrors('email');

        $this->assertEquals(0, User::count());
    }

    /**
     * @test
     */
    public function the_email_is_valid()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', [
            'name' => 'David',
            'email' => 'uncorreo',
            'password' => '123456'
        ])->assertRedirect('users')
            ->assertSessionHasErrors('email');

        $this->assertEquals(0, User::count());
    }

    /**
     * @test
     */
    public function the_email_is_unique()
    {
        factory(User::class)->create([
            'email' => 'ccristhiangarcia@gmail.com'
        ]);

        $this->from(route('users'))
            ->post('users', [
            'name' => 'David',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ])->assertRedirect('users')
            ->assertSessionHasErrors('email');

        $this->assertEquals(1, User::count());
    }

    /**
     * @test
     */
    public function the_password_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', [
            'name' => 'David',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => ''
        ])->assertRedirect('users')
            ->assertSessionHasErrors('password');

        $this->assertEquals(0, User::count());
    }

    /**
     * @test
     */
    public function the_name_is_required_when_updates_user()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from("users/{$user->id}/edit")
            ->put("users/{$user->id}", [
            'name' => '',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ])->assertRedirect("users/{$user->id}/edit")
            ->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('users', [
            'email' => 'ccristhiangarcia@gmail.com',
        ]);
    }

    /**
     * @test
     */
    public function the_email_is_required_when_updates_user()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from("users/{$user->id}/edit")
            ->put("users/{$user->id}", [
            'name' => 'David',
            'email' => '',
            'password' => '123456'
        ])->assertRedirect("users/{$user->id}/edit")
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', ['name' => 'David']);
    }

    /**
     * @test
     */
    public function the_email_is_valid_when_updates_user()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from(route('users.edit', $user->id))
            ->put("users/{$user->id}", [
            'name' => 'David',
            'email' => 'uncorreo',
            'password' => '123456'
        ])->assertRedirect("users/{$user->id}/edit")
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', ['email' => 'uncorreo']);
    }

    /**
     * @test
     */
    public function the_email_is_unique_when_updates_user()
    {
        // $this->withoutExceptionHandling();

        factory(User::class)->create([
            'email' => 'ccristhiangarcia@gmail.com'
        ]);

        $this->from(route('users'))
            ->post('users', [
            'name' => 'David',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ])->assertRedirect('users')
            ->assertSessionHasErrors('email');

        // 
    }

    /**
     * @test
     */
    public function it_users_can_delete()
    {
        $this->withoutExceptionHandling();
        
        $user = factory(User::class)->create();

        $this->delete("users/$user->id")
            ->assertRedirect('users');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /**
     * @test
     */
    public function the_users_email_can_stay_the_same_when_updating_the_user()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'email' => 'ccristhiangarcia@gmail.com'
        ]);

        $this->from(route('users.edit', $user->id))
            ->put("users/{$user->id}", [
            'name' => 'David',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '1234567'
        ])->assertRedirect("users/");

        $this->assertDatabaseHas('users', [
            'name' => 'David',
            'email' => 'ccristhiangarcia@gmail.com',
        ]);
    }

    /**
     * @test
     */
    public function the_password_is_optional_when_updates_user()
    {
        // $this->withoutExceptionHandling();
        $oldPassword = 'OLD_PASSWORD';

        $user = factory(User::class)->create([
            'password' => bcrypt($oldPassword)
        ]);

        $this->from(route('users.edit', $user->id))
            ->put("users/{$user->id}", [
            'name' => 'David',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => ''
        ])->assertRedirect("users/");

        $this->assertCredentials([
            'name' => 'David',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => $oldPassword
        ]);
    }
    
    /**
     * @test
     */
    public function it_loads_the_edit_user_page()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->get("users/{$user->id}/edit")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar usuario')
            ->assertViewHas('user', function ($Viewuser) use ($user) {
                return $Viewuser->id == $user->id;
            });
    }

    /**
     * @tests
     */
    public function it_not_loads_the_edit_users_page_with_text()
    {
        $this->get('users/text/edit')
            ->assertStatus(404)
            ->assertSee('Pagina no encontrada');
    }

    /**
     * @test
     */
    public function it_updates_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->put("users/{$user->id}/", [
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ])->assertRedirect('users');

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ]);
    }
}
