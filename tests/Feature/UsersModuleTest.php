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
     * The user profession
     *
     * @var App\Profession
     */
    protected $profession;

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

        $profession = factory(\App\Profession::class)->create();

        $this->get('users/create')
            ->assertStatus(200)
            ->assertSee('Crear usuario')
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            });
    }

    /**
     * @test
     */
    public function it_create_a_new_user()
    {
        $this->withoutExceptionHandling();

        $this->post('users', $this->getValidData())
            ->assertRedirect(route('users.store'));

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/davidccgarcia',
            'user_id' => User::findByEmail('ccristhiangarcia@gmail.com')->id,
            'profession_id' => $this->profession->id,
        ]);
    }

    /**
     * @test
     */
    public function the_twitter_field_is_optional()
    {
        $this->withoutExceptionHandling();

        $this->post('users', $this->getValidData([
            'twitter' => null,
        ]))->assertRedirect(route('users.store'));

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'twitter' => null,
            'user_id' => User::findByEmail('ccristhiangarcia@gmail.com')->id,
        ]);
    }

    /**
     * @test
     */
    public function the_profession_id_field_is_optional()
    {
        $this->withoutExceptionHandling();

        $this->post('users', $this->getValidData([
            'profession_id' => null,
        ]))->assertRedirect(route('users.store'));

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'user_id' => User::findByEmail('ccristhiangarcia@gmail.com')->id,
            'profession_id' => null,
        ]);
    }

    /**
     * @test
     */
    public function the_name_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', $this->getValidData([
                'name' => '',
            ]))->assertRedirect('users')
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_profession_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from(route('users'))
            ->post('users', $this->getValidData([
                'profession_id' => '999',
            ]))->assertRedirect('users')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function only_no_deleted_at_professions_are_valid()
    {
        $deletedProfession = factory(\App\Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d'),
        ]);

        $this->handleValidationExceptions();

        $this->from(route('users.create'))
            ->post('users', $this->getValidData([
                'profession_id' => $deletedProfession->id,
            ]))->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_email_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', $this->getValidData([
                'email' => '',
            ]))->assertRedirect('users')
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_email_is_valid()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', $this->getValidData([
                'email' => 'correo-no-valido'
            ]))->assertRedirect('users')
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseEmpty('users');
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
            ->post('users', $this->getValidData([
                'email' => 'ccristhiangarcia@gmail.com',
            ]))->assertRedirect('users')
                ->assertSessionHasErrors(['email']);

        // $this->assertEquals(1, User::count());
        $this->assertDatabaseCount('users');
    }

    /**
     * @test
     */
    public function the_password_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->from(route('users'))
            ->post('users', $this->getValidData([
                'password' => ''
            ]))->assertRedirect('users')
            ->assertSessionHasErrors(['password']);

        $this->assertDatabaseEmpty('users');
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
            ->assertSessionHasErrors(['name']);

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
            ->assertSessionHasErrors(['email']);

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
            ->assertSessionHasErrors(['email']);

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
            ->assertSessionHasErrors(['email']);

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

    public function getValidData(array $custom = [])
    {
        $this->profession = factory(\App\Profession::class)->create();

        return array_merge([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456',
            'profession_id' => $this->profession->id,
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/davidccgarcia'
        ], $custom);
    }
}
