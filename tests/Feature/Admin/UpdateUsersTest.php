<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'David Garcia',
        'email' => 'ccristhiangarcia@gmail.com',
        'password' => '123456',
        'bio' => 'Desarrollador de Laravel y Vue.js',
        'profession_id' => '',
        'twitter' => 'https://twitter.com/davidccgarcia',
        'role' => 'user',
    ];

    /**
     * @test
     */
    public function the_name_is_required()
    {
        $this->handleValidationExceptions();

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
    public function the_email_is_required()
    {
        $this->handleValidationExceptions();

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
    public function the_email_is_valid()
    {
        $this->handleValidationExceptions();

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
    public function the_email_is_unique()
    {
        $this->handleValidationExceptions();

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
    public function the_users_email_can_stay_the_same()
    {
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
    public function the_password_is_optional()
    {
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
